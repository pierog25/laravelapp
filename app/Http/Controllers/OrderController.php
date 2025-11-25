<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderDetail;
use App\Factories\OrderFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionMailable;
use Carbon\Carbon;
use PDF;
use App\Services\QuotationPdfFacade;



class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $documentNumber = $request->input('document_number');
        $fromDate = $request->input('desde', now()->toDateString());
        $toDate = $request->input('hasta', now()->toDateString());

        $ordersQuery = Order::where('status', true)
            ->with([
                'client',
                'user',
                'details' => function ($query) {
                    $query->where('status', true);
                }
            ]);

        if (!empty($documentNumber)) {
            $ordersQuery->whereHas('client', function ($query) use ($documentNumber) {
                $query->where('document_number', $documentNumber);
            });
        }

        if ($fromDate && $toDate) {
            $ordersQuery->whereBetween('issue_date', [$fromDate, $toDate]);
        }
        $orders = $ordersQuery
            ->orderBy('issue_date','desc')
            ->get();

        return response()->json([
            'success' => true,
            'msg' => 'Órdenes obtenidas correctamente.',
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'delivery_date' => 'required|date',
            'delivery_location' => 'required|string',
            'issue_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'order_details' => 'required|array|min:1',
            'order_details.*.product_id' => 'required|exists:products,id',
            'order_details.*.description' => 'nullable|string',
            'order_details.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            // FACTORY METHOD
            $order = OrderFactory::createFromArray($validated);

            // Cargamos relaciones
            $order->load(['client', 'user', 'details']);

            // Email
            $email = $order->client->email;
            $name = $order->client->first_name . " " . $order->client->last_name;
            $id = $order->id;
            $idFormated = str_pad($id, 8, '0', STR_PAD_LEFT);

            // Mail::to($email)->send(new NotificacionMailable(
            //     $name,
            //     "Por Cotizar",
            //     $idFormated,
            //     null,
            //     null
            // ));

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Orden creada correctamente.',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'msg' => 'Ocurrió un error al crear la orden.',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::where('status', true)
            ->with([
                'client',
                'user',
                'details' => function ($query) {
                    $query->where('status', true);
                }
            ])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'msg' => 'Orden obtenida correctamente.',
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'delivery_date' => 'required|date',
            'delivery_location' => 'required|string',
            'issue_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'status'  => 'boolean',
            'order_details' => 'nullable|array',
            'order_details.*.id' => 'sometimes|integer|exists:order_details,id',
            'order_details.*.product_id' => 'required_with:order_details|exists:products,id',
            'order_details.*.description' => 'nullable|string',
            'order_details.*.quantity' => 'required_with:order_details|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            $order = Order::findOrFail($id);
            $order->update($validated);

            $existingDetails = $order->details()->where('status', true)->get();
            $incomingIds = collect($validated['order_details'] ?? [])->pluck('id')->filter();

            // 1. Marcar como eliminados los detalles que ya existen pero no vienen en el update
            foreach ($existingDetails as $detail) {
                if (!$incomingIds->contains($detail->id)) {
                    $detail->update(['status' => false]);
                }
            }

            // 2. Procesar cada detalle enviado
            foreach ($validated['order_details'] ?? [] as $detailData) {
                if (!empty($detailData['id'])) {
                    // Editar detalle existente
                    $detail = OrderDetail::find($detailData['id']);
                    if ($detail) {
                        $detail->update([
                            'product_id' => $detailData['product_id'],
                            'description' => $detailData['description'] ?? null,
                            'quantity' => $detailData['quantity'],
                            'status' => true
                        ]);
                    }
                } else {
                    $order->details()->create([
                        'product_id' => $detailData['product_id'],
                        'description' => $detailData['description'] ?? null,
                        'quantity' => $detailData['quantity'],
                        'status' => true
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Orden actualizada correctamente.',
                'data' => $order->load([
                    'client',
                    'user',
                    'details' => function ($query) {
                        $query->where('status', true);
                    }
                ])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'msg' => 'Ocurrió un error al actualizar la orden.',
                'data' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $order = Order::findOrFail($id);
            $order->status = false;
            $order->save();

            foreach ($order->order_details as $detail) {
                $detail->status = false;
                $detail->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Orden eliminada correctamente.',
                'data' => []
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'msg' => 'Ocurrió un error al eliminar la orden.',
                'data' => []
            ], 500);
        }
    }

    public function getOrder(Request $request){
        $code = $request->input('code');
        // QUIERO VALIDAR QUE CODE SEA NUMERICO
        if(!$code){
            return response()->json([
                'success' => false,
                'msg' => 'No existe pedido con ese código.',
                'data' => []
            ], 400);
        }
        if (!is_numeric($code)) {
            return response()->json([
                'success' => false,
                'msg' => 'No existe pedido con ese código.',
                'data' => []
            ], 400);
        }

        $ordersQuery = Order::where('status', true)
            ->with([
                'client',
                'user',
                'details' => function ($query) {
                    $query->where('status', true)
                        ->with(['preSaleReport' => function ($q) {
                            $q->where('status', true)
                                ->with(['details' => function ($d) {
                                    $d->where('status', true)
                                        ->with(['supplier']);
                                }]);
                        }]);
                }
//                'details' => function ($query) {
//                    $query->where('status', true);
//                }
            ]);

        if (!empty($code)) {
            $ordersQuery->where('id', $code);
        }
        $orders = $ordersQuery->first();

        return response()->json([
            'success' =>!is_null($orders),
            'msg' =>!is_null($orders)  ? 'Órdenes obtenidas correctamente.': "No existe pedido con ese código.",
            'data' => $orders
        ]);
    }

    public function pdf($id, QuotationPdfFacade $quotationPdfFacade)
    {
        return $quotationPdfFacade->stream((int) $id);
    }

}