<?php

namespace App\Http\Controllers;
use App\Client;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Client::with('documentType');

        if ($request->has('document')) {
            $query->where('document_number', $request->document);
        }

        if ($request->has('name')) {
            $name = $request->name;
            $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$name}%"]);
        }

        $clients = $query->get();

        return response()->json($clients);
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
            'id_document_type' => 'required|exists:document_types,id',
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'document_number' => 'required|string|max:20',
            'phone'           => 'required|string|max:15',
            'email'           => 'nullable|email',
            'address'         => 'nullable|string',
            'status'          => 'boolean',
        ]);

        $client = Client::create($validated);

        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::with('documentType')->findOrFail($id);
        return response()->json($client);
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
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'id_document_type' => 'required|exists:document_types,id',
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'document_number' => 'required|string|max:20',
            'phone'           => 'required|string|max:15',
            'email'           => 'nullable|email',
            'address'         => 'nullable|string',
            'status'          => 'boolean',
        ]);

        $client->update($validated);

        return response()->json($client);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->status = false;
        $client->save();
        return response()->json([]);
    }
}
