<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderDetail;
use App\PreSaleReport;
use App\PreSaleReportDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PreSaleReportController extends Controller
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

        $ordersQuery->where('order_status', 'Por Cotizar');
        $ordersQuery->whereBetween('issue_date', [$fromDate, $toDate]);
        $orders = $ordersQuery->get();

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
            'order_id' => 'required|exists:orders,id',
            'details' => 'required|array|min:1',
            'details.*.order_detail_id' => 'required|exists:order_details,id',
            'details.*.details' => 'required|array|min:1',
            'details.*.details.*.resource' => 'required|string',
            'details.*.details.*.supplier_id' => 'required|exists:suppliers,id',
            'details.*.details.*.cost' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            $order = Order::with('details')->findOrFail($validated['order_id']);
            $orderDetails = $order->details->keyBy('id');
            $results = [];

            foreach ($validated['details'] as $orderDetailData) {
                $detailId = $orderDetailData['order_detail_id'];

                if (!$orderDetails->has($detailId)) {
                    continue;
                }

                $orderDetail = $orderDetails[$detailId];
                $quantity = $orderDetail->quantity;

                $preSale = PreSaleReport::create([
                    'order_detail_id' => $orderDetail->id,
                    'unit_price' => 0,
                    'status' => true
                ]);

                $totalCost = 0;

                foreach ($orderDetailData['details'] as $detail) {
                    $totalCost += $detail['cost'];

                    PreSaleReportDetail::create([
                        'pre_sale_report_id' => $preSale->id,
                        'resource' => $detail['resource'],
                        'supplier_id' => $detail['supplier_id'],
                        'cost' => $detail['cost'],
                        'status' => true
                    ]);
                }

                $totalWithMargin = $totalCost * 1.3;
                $unitPrice = $quantity > 0 ? round($totalWithMargin / $quantity, 2) : 0;

                $preSale->unit_price = $unitPrice;
                $preSale->save();

                $results[] = $preSale->load('details');
            }

            $order->order_status = "Cotizado";
            $order->save();
            DB::commit();

            return response()->json([
                'message' => 'Preventas creadas correctamente.',
                'data' => $results
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
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
        $preSale = Order::where('status', true)
            ->with([
                'details' => function ($query) {
                    $query->where('status', true)
                        ->with(['preSaleReport' => function ($q) {
                            $q->where('status', true)
                                ->with(['details' => function ($d) {
                                    $d->where('status', true);
                                }]);
                        }]);
                }
            ])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'msg' => 'Preventa obtenida correctamente.',
            'data' => $preSale
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
            'details' => 'required|array|min:1',
            'details.*.order_detail_id' => 'required|exists:order_details,id',
            'details.*.details' => 'required|array|min:1',
            'details.*.details.*.id' => 'nullable|exists:pre_sale_report_details,id',
            'details.*.details.*.resource' => 'required|string',
            'details.*.details.*.supplier_id' => 'required|exists:suppliers,id',
            'details.*.details.*.cost' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            $order = Order::with('details.preSaleReport.details')->findOrFail($id);
            $results = [];

            // Indexar los OrderDetails por ID para acceso rápido
            $orderDetailsMap = $order->details->keyBy('id');

            foreach ($validated['details'] as $detailGroup) {
                $orderDetailId = $detailGroup['order_detail_id'];

                if (!isset($orderDetailsMap[$orderDetailId])) {
                    continue;
                }

                $orderDetail = $orderDetailsMap[$orderDetailId];
                $quantity = $orderDetail->quantity;

                // Obtener o crear el PreSaleReport
                $preSale = $orderDetail->preSaleReport ?? PreSaleReport::create([
                    'order_detail_id' => $orderDetail->id,
                    'unit_price' => 0,
                    'status' => true
                ]);

                // Indexar detalles actuales por ID
                $existingDetails = $preSale->details->keyBy('id');
                $usedDetailIds = [];
                $totalCost = 0;

                foreach ($detailGroup['details'] as $detailInput) {
                    if (!empty($detailInput['id']) && isset($existingDetails[$detailInput['id']])) {
                        $detail = $existingDetails[$detailInput['id']];
                        $detail->update([
                            'resource' => $detailInput['resource'],
                            'supplier_id' => $detailInput['supplier_id'],
                            'cost' => $detailInput['cost'],
                            'status' => true
                        ]);
                        $usedDetailIds[] = $detail->id;
                        $totalCost += $detailInput['cost'];
                    } else {
                        $newDetail = PreSaleReportDetail::create([
                            'pre_sale_report_id' => $preSale->id,
                            'resource' => $detailInput['resource'],
                            'supplier_id' => $detailInput['supplier_id'],
                            'cost' => $detailInput['cost'],
                            'status' => true
                        ]);
                        $usedDetailIds[] = $newDetail->id;
                        $totalCost += $detailInput['cost'];
                    }
                }

                // Marcar como eliminados los que ya no están
                $preSale->details->whereNotIn('id', $usedDetailIds)->each(function ($detail) {
                    $detail->update(['status' => false]);
                });

                // Recalcular precio unitario
                $totalWithMargin = $totalCost * 1.3;
                $unitPrice = $quantity > 0 ? round($totalWithMargin / $quantity, 2) : 0;

                $preSale->update(['unit_price' => $unitPrice]);

                $results[] = $preSale->fresh([
                    'details' => function ($query) {
                        $query->where('status', true);
                    }
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Preventas actualizadas correctamente.',
                'data' => $results
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
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
            $preSale = PreSaleReport::findOrFail($id);
            $preSale->status = false;
            $preSale->save();

            // Actualización masiva de detalles
            PreSaleReportDetail::where('pre_sale_report_id', $preSale->id)->update(['status' => false]);

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Preventa eliminada correctamente.',
                'data' => []
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'msg' => 'Ocurrió un error al eliminar la preventa.',
                'data' => []
            ], 500);
        }
    }
}
