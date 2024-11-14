<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataPaket = Paket::all();

        return view('paket.index', [
            'paketInet' => $dataPaket
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         // Initiate client with config object
        $client = new Client(config('mikrotik.credential'));

        // Create "where" Query object for RouterOS
        $query =
            (new Query('/ppp/profile/print'));

        // Send query and read response from RouterOS
        $pppProfile = $client->query($query)->read();

        return view('paket.create', [
            'ppp' => $pppProfile
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
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
