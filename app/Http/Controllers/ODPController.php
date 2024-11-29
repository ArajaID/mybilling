<?php

namespace App\Http\Controllers;

use App\Models\DataODC;
use App\Models\DataODP;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ODPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataODP = DataODP::all();

        return view('odp.index', [
            'odp' => $dataODP,
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dataODC = DataODC::all();

        return view('odp.create', [
            'odc' => $dataODC,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'odc_id'    => 'required',
            'odp'       => 'required',
            'lokasi'    => 'required',
            'port'      => 'required',
        ]);

        $validateData['odp'] = Str::upper($request->odp);

        DataODP::create($validateData);

        toast('ODP berhasil ditambah!','success');
        return redirect()->route('odp.index');
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
