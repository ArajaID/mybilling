<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\DataODC;
use App\Models\DataODP;
use App\Models\Tagihan;
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
        $dataPelanggan = Pelanggan::with('paket')->paginate(3);

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
        $dataODC = DataODC::all();

        return view('pelanggan.create', [
            'kodePelanggan' => $randomKodePelanggan,
            'paketInet' => $paketInternet,
            'dataODC' => $dataODC
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
            'odp_id'           => 'required',
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
        $dataPelanggan = Pelanggan::findOrFail($id);
        $historyTagihan = Tagihan::where('id_pelanggan', $id)->where('status_pembayaran', 'LUNAS')->paginate(10);

        return view('pelanggan.show', [
            'pelanggan' => $dataPelanggan,
            'historyTagihan' => $historyTagihan
        ]);
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

    public function getODP($odc_id)
    {
        $dataODP = DataODP::where('odc_id', $odc_id)->get();
        return response()->json($dataODP);
    }
}
