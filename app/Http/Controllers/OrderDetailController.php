<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderDetail;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return OrderDetail::with(['order', 'product'])->get();
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
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $detail = OrderDetail::create($validated);
        return response()->json($detail, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return OrderDetail::with(['order', 'product'])->findOrFail($id);
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
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'status'  => 'boolean',
        ]);

        $detail = OrderDetail::findOrFail($id);
        $detail->update($validated);

        return response()->json($detail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = OrderDetail::findOrFail($id);
        $client->status = false;
        $client->save();
        return response()->json([]);
    }
}
