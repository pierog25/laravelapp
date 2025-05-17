<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreSaleReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preSale = Order::where('status', true)
            ->with([
                'details' => function ($query) {
                    $query->where('status', true);
                }
            ])
            ->get();

        return response()->json([
            'success' => true,
            'msg' => 'Preventa activas obtenidas correctamente.',
            'data' => $preSale
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
            'order_detail_id' => 'required|exists:order_details,id',
            'details' => 'required|array|min:1',
            'details.*.resource' => 'required|string',
            'details.*.supplier_id' => 'required|exists:suppliers,id',
            'details.*.cost' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $orderDetail = OrderDetail::findOrFail($validated['order_detail_id']);
            $quantity = $orderDetail->quantity;


            $preSale = PreSaleReport::create([
                'order_detail_id' => $validated['order_detail_id'],
                'unit_price' => 0,
                'status' => true
            ]);
            
            $totalCost = 0;

            // Crear detalles y acumular costo
            foreach ($validated['details'] as $detail) {
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

            DB::commit();
            return response()->json([
                'message' => 'Preventa creada correctamente.',
                'data' => $preSale->load('details')
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
        $preSale = PreSaleReport::where('status', true)
            ->with([
                'details' => function ($query) {
                    $query->where('status', true);
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
        //
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

            foreach ($preSale->details as $detail) {
                $detail->status = false;
                $detail->save();
            }

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
