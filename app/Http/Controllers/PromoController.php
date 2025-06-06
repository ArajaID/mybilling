<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Promo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promo = Promo::where('id', '>', 1)->get();

        return view('promo.index', [
            'dataPromo' => $promo
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kodePromo = Str::random(10);
        $paket = Paket::all();

        return view('promo.create', [
            'kodePromo' => $kodePromo,
            'paket' => $paket
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kode_promo'        => 'required',
            'nama_promo'        => 'required',
            'deskripsi'         => 'nullable',
            'diskon'            => 'nullable',
            'tambahan_speed'    => 'nullable',
            'tanggal_mulai'     => 'required',
            'tanggal_berakhir'  => 'required',
            'syarat_ketentuan'  => 'nullable',
        ]);

        Promo::create($validateData);

        toast('Promo berhasil ditambah!','success');
        return redirect()->route('promo.index');
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
