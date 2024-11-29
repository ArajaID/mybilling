<?php

namespace App\Http\Controllers;

use App\Models\DataODC;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ODCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataODC = DataODC::all();

        return view('odc.index', [
            'odc' => $dataODC
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('odc.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'odc_induk' => 'required',
            'odc'       => 'required',
            'lokasi'    => 'required',
            'port'      => 'required',
        ]);

        $validateData['odc_induk'] = Str::upper($request->odc_induk);
        $validateData['odc'] = Str::upper($request->odc);

        DataODC::create($validateData);

        toast('ODC berhasil ditambah!','success');
        return redirect()->route('odc.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
