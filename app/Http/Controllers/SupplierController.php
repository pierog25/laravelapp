<?php

namespace App\Http\Controllers;
use App\Supplier;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Supplier::all();
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
            'name' => 'required|string|max:255',
            'ruc' => 'required|string|size:11',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'first_phone' => 'nullable|string|max:15',
            'second_phone' => 'nullable|string|max:15',
            'third_phone' => 'nullable|string|max:15',
            'type' => 'in:Materiales,Servicios',
            'status' => 'boolean',
        ]);

        $supplier = Supplier::create($validated);
        return response()->json($supplier, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier, 200);
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
            'name' => 'sometimes|required|string|max:255',
            'ruc' => 'sometimes|required|string|size:11',
            'address' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'first_phone' => 'nullable|string|max:15',
            'second_phone' => 'nullable|string|max:15',
            'third_phone' => 'nullable|string|max:15',
            'type' => 'sometimes|required|in:Materials,Services',
            'status' => 'boolean',
        ]);
        
        $supplier = Supplier::findOrFail($id);
        $supplier->update($validated);
        return response()->json($supplier, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Supplier::findOrFail($id);
        $client->status = false;
        $client->save();
        return response()->json([]);
    }
}
