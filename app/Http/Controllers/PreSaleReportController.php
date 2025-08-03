<?php

namespace App\Http\Controllers;

use App\Order;
use App\Supplier;
use App\OrderDetail;
use App\PreSaleReport;
use App\PreSaleReportDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionMailable;


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
                    $query->where('status', true)
                        ->with(['preSaleReport' => function ($q) {
                            $q->where('status', true)
                                ->with(['details' => function ($d) {
                                    $d->where('status', true)
                                    ->with(['supplier']);
                                }]);
                        }]);
                }
            ]);

        if (!empty($documentNumber)) {
            $ordersQuery->whereHas('client', function ($query) use ($documentNumber) {
                $query->where('document_number', $documentNumber);
            });
        }
        $status = $request->status;
        $ordersQuery->whereIn('order_status', $status);
        if($fromDate && $toDate){
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
            'order_id' => 'required|exists:orders,id',
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
            $order = Order::with('details.preSaleReport.details')->findOrFail($validated["order_id"]);
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

            $order->order_status = "Cotizado";
            $order->save();

            $order->load('client');

            $name = $order->client->first_name." ".$order->client->last_name; // nombre cliente
            $idFormated = str_pad($order->id, 8, '0', STR_PAD_LEFT);
            $email = $order->client->email;

            Mail::to($email)->send(new NotificacionMailable(
                $name,
                "Cotizado",
                $idFormated,
                null,
                null
            ));

            DB::commit();

            return response()->json([
                'message' => 'Preventa actualizada correctamente.',
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

    public function saveRow(Request $request){
        if ($request->date_delivery) {
            $idFormated = str_pad($request->order, 8, '0', STR_PAD_LEFT);
            $email = $request->client_email;
            Mail::to($email)->send(new NotificacionMailable(
                $request->client,
                "En Marcha",
                $idFormated,
                null,
                null
            ));
        }
        $update = PreSaleReportDetail::where('id', $request->id)
            ->update($request->only([
                'comment_quality',
                'comments_cost',
                'completed',
                'cost_additional',
                'cost_final',
                'date_delivery',
                'date_receipt',
                'img',
                'material_quality',
                'material_relation',
                'service_qty_defective',
                'service_qty_total',
            ]));
        if($update){
            return response()->json([
                'success' => true,
                'msg' => "Detalle actualizado correctamente."
            ]);
        }
        return response()->json([
            'success' => false,
            'msg' => "Detalle no actualizado"
        ]);
    }
    public function completedRow(Request $request)
    {
        if(!$request->date_delivery){
            return response()->json([
                'success' => false,
                'msg' => "Falta la fecha de entrega."
            ]);
        }
        if(!$request->date_receipt){
            return response()->json([
                'success' => false,
                'msg' => "Falta la fecha de recepción."
            ]);
        }
        if(!$request->cost_final){
            return response()->json([
                'success' => false,
                'msg' => "Falta el costo final."
            ]);
        }
        if(!$request->img){
            return response()->json([
                'success' => false,
                'msg' => "Falta la imagen."
            ]);
        }

        if(!$request->material_quality){
            return response()->json([
                'success' => false,
                'msg' => "Falta la evalución de calidad del material."
            ]);
        }
        if(!$request->material_relation){
            return response()->json([
                'success' => false,
                'msg' => "Falta la evalución de la relación calidad - precio  del material."
            ]);
        }
        if(!$request->service_qty_total){
            return response()->json([
                'success' => false,
                'msg' => "Falta la cantidad de productos totales"
            ]);
        }
        $detail = $request->only([
            'comment_quality',
            'comments_cost',
            'completed',
            'cost_additional',
            'cost_final',
            'date_delivery',
            'date_receipt',
            'img',
            'material_quality',
            'material_relation',
            'service_qty_defective',
            'service_qty_total',
        ]);
        $detail["completed"] = 1;
        $update = PreSaleReportDetail::where('id', $request->id)
            ->update($detail);
        if ($update) {
            return response()->json([
                'success' => true,
                'msg' => "Detalle actualizado correctamente."
            ]);
        }
        return response()->json([
            'success' => false,
            'msg' => "Detalle no actualizado"
        ]);
    }
    public function report(){
        $orders = Order::with('details.preSaleReport.details')->get();
        $allDetails = collect();
        foreach ($orders as $order) {
            foreach ($order->details as $orderDetail) {
                if ($orderDetail->preSaleReport && $orderDetail->preSaleReport->details) {
                    foreach ($orderDetail->preSaleReport->details as $preSaleDetail) {
                        $allDetails->push($preSaleDetail);
                    }
                }
            }
        }
        $grouped = $allDetails->groupBy('supplier_id');
        $customersGroup = Supplier::select(["id","name"])->get()->keyBy('id');
        $result = [];
        foreach ($grouped as $supplier_id => $details) {
            $tiempoArr = [];
            $costosArr = [];
            $calidadArr = [];
            $generalArr = [];

            foreach ($details as $detail) {
                // --- Tiempo ---
                $dias = Carbon::parse($detail['date_delivery'])->diffInDays(Carbon::parse($detail['date_receipt']), false);
                if ($dias < 0) {
                    $ptj_tiempo = 3;
                } elseif ($dias >= 1) {
                    $ptj_tiempo = 1;
                } else {
                    $ptj_tiempo = 2;
                }

                // --- Costos ---
                $cost = $detail['cost'];
                $cost_final = $detail['cost_final'];
                $cost_additional = $detail['cost_additional'];
                $total_final = $cost_final + $cost_additional;
                $incremento = $total_final - $cost;
                $porcentaje = ($cost > 0) ? ($incremento / $cost) * 100 : 0;
                if ($porcentaje > 15) {
                    $ptj_costos = 1;
                } elseif ($porcentaje <= -10) {
                    $ptj_costos = 3;
                } else {
                    $ptj_costos = 2;
                }

                // --- Calidad: material ---
                $mat_qual = $detail['material_quality'];
                $mat_rel = $detail['material_relation'];
                $p1 = $mat_qual;
                $p2 = $mat_rel;
                $mat_score = ($p1 + $p2) / 2;

                // --- Calidad: servicio ---
                $defective = $detail['service_qty_defective'];
                $total = $detail['service_qty_total'];
                $perc_ok = ($total > 0) ? 100 - ($defective / $total) * 100 : 0;
                if ($perc_ok < 85) {
                    $p3 = 1;
                } elseif ($perc_ok < 100) {
                    $p3 = 2;
                } else {
                    $p3 = 3;
                }

                // --- Calidad final ---
                $ptj_calidad = ($mat_score + $p3) / 2; // la anterior, pero ahora según nueva regla:
                $ptj_calidad = ($p1 + $p2 + $p3) / 3; // USAR ESTA COMO DEFINISTE AHORA

                // --- General ---
                $ptj_general = round(($ptj_calidad * 0.45) + ($ptj_tiempo * 0.30) + ($ptj_costos * 0.25), 2);

                // --- Labels ---
                $label = $detail['resource']; // O puedes usar el producto, o cualquier campo como label

                // --- Appends ---
                $tiempoArr[] = ['label' => $label, 'value' => $ptj_tiempo];
                $costosArr[] = ['label' => $label, 'value' => $ptj_costos];
                $calidadArr[] = ['label' => $label, 'value' => round($ptj_calidad, 2)];
                $generalArr[] = ['label' => $label, 'value' => $ptj_general];
            }

            $result[$supplier_id] = [
                'nombre' => $customersGroup[$supplier_id]->name,
                'tiempo' => round(collect($tiempoArr)->pluck('value')->avg(), 2),
                'costos' => round(collect($costosArr)->pluck('value')->avg(), 2),
                'calidad' => round(collect($calidadArr)->pluck('value')->avg(), 2),
                'general' => round(collect($generalArr)->pluck('value')->avg(), 2),
            ];
        }

        return $result;
    }
    public function production(Request $request){

        $order = Order::with('client')->find($request->id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'msg' => "Orden no encontrada."
            ]);
        }

        // Actualiza el estado y la fecha de entrega
        $order->order_status = 'En Producción';
        $order->delivery_date = date("Y-m-d");
        $order->save();

        // Datos para el email
        $name = $order->client->first_name . ' ' . $order->client->last_name; // Ajusta si tu modelo es diferente
        $idFormated = str_pad($order->id, 8, '0', STR_PAD_LEFT);
        $email = $order->client->email;

        // Envía el correo
        Mail::to($email)->send(new NotificacionMailable(
            $name,
            "En Producción",
            $idFormated,
            null,
            null
        ));

        // Respuesta exitosa
        return response()->json([
            'success' => true,
            'msg' => "Cotización marcada en producción"
        ]);
    }

    public function delivery(Request $request)
    {
        $order = Order::with('client','details.preSaleReport.details')->findOrFail($request->id);

// Inicializa un array para guardar todos los details de los preSaleReports
        $allPreSaleDetails = [];

// Recorre los detalles de la orden
        foreach ($order->details as $detail) {
            // Si tiene preSaleReport y ese preSaleReport tiene details
            if ($detail->preSaleReport && $detail->preSaleReport->details) {
                foreach ($detail->preSaleReport->details as $preSaleDetail) {
                    $allPreSaleDetails[] = $preSaleDetail;
                }
            }
        }
        $todosCompletos = collect($allPreSaleDetails)->every(function ($detalle) {
            return $detalle->completed == 1;
        });
        if ($todosCompletos) {

            $name = $order->client->first_name . " " . $order->client->last_name;
            $idFormated = str_pad($order->id, 8, '0', STR_PAD_LEFT);
            $email = $order->client->email;
            $link = "https://jbctextil.com/formulario/".$order->id; // ejemplo

            Mail::to($email)->send(new NotificacionMailable(
                $name,
                "Entregado",
                $idFormated,
                null,
                $link      // aquí pasas el link
            ));

            $update = Order::where("id", $request->id)->update([
                'order_status' => 'Entregado',
                'delivery_date' => date("Y-m-d")
            ]);
            if ($update) {
                return response()->json([
                    'success' => true,
                    'msg' => "Preventa marcada como entregada correctamente."
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => "No se pudo marcar la preventa como entregada."
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'msg' => "Te faltan completar algunos detalles de la preventa antes de marcarla como entregada."
            ]);
        }
    }
}
