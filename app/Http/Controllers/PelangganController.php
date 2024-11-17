<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Pelanggan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataPelanggan = Pelanggan::with('paket')->get();

        return view('pelanggan.index', [
            'pelanggan' => $dataPelanggan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $randomKodePelanggan = mt_rand(100000, 999999);
        $paketInternet = Paket::all();

        return view('pelanggan.create', [
            'kodePelanggan' => $randomKodePelanggan,
            'paketInet' => $paketInternet
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kode_pelanggan'    => 'required',
            'nama_pelanggan'    => 'required',
            'email'             => 'nullable|email',
            'no_telepon'        => 'nullable',
            'blok'              => 'nullable',
            'rt'                => 'nullable',
            'area'              => 'required',
            'odc_odp'           => 'required',
            'id_paket'          => 'required',
        ]);

        if($request->area == 'perumahan') {
            $userPPPoE = Str::lower($request->blok . '-' . $request->rt);
        } else {
            $userPPPoE = $request->kode_pelanggan;
        }

        $validateData['user_pppoe'] = $userPPPoE;
        $validateData['password_pppoe'] = Str::upper(Str::random(5));
        $validateData['nama_pelanggan'] = Str::title($request->nama_pelanggan);

        Pelanggan::create($validateData);

        toast('Pelanggan berhasil ditambah!','success');
        return redirect()->route('pelanggan.index');
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
