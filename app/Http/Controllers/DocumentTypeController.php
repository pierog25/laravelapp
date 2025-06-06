<?php

namespace App\Http\Controllers;
use App\DocumentType;

use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(DocumentType::all());
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
            'name' => 'required|string|max:30',
        ]);

        $documentType = DocumentType::create($validated);

        return response()->json($documentType, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $documentType = DocumentType::findOrFail($id);
        return response()->json($documentType);
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
        $documentType = DocumentType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'status' => 'boolean',
        ]);

        $documentType->update($validated);

        return response()->json($documentType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = DocumentType::findOrFail($id);
        $client->status = false;
        $client->save();
        return response()->json([]);
    }
}
