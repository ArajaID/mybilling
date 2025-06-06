<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Paket;
use App\Models\Promo;
use App\Models\DataODC;
use App\Models\DataODP;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PromoPelanggan;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('keyword');

        $dataPelanggan = Pelanggan::with('paket')
        ->search($search)
        ->where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('pelanggan.index', [
            'pelanggan' => $dataPelanggan,
            'search'    => $search
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
            'paketInet'     => $paketInternet,
            'dataODC'       => $dataODC
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
            'odp_id'            => 'required',
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
        $historyTagihan = Tagihan::where('id_pelanggan', $id)->paginate(10);
        $promoPelanggan = PromoPelanggan::where('id_pelanggan', $id)->first();
        if($promoPelanggan) {
            $promoActive = Promo::findOrFail($promoPelanggan->id_promo);
        } else {
            $promoActive = "";
        }

        return view('pelanggan.show', [
            'pelanggan'         => $dataPelanggan,
            'historyTagihan'    => $historyTagihan,
            'promoActive'       => $promoActive,
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

    public function deactivate(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        if ($pelanggan->is_active) {
            $request->validate([
                'alasan_nonaktif' => 'required|string|max:255',
            ]);
    
            $pelanggan->is_active = false;
            $pelanggan->alasan_nonaktif = $request->alasan_nonaktif;
            $pelanggan->save();

            $client = new Client(config('mikrotik.credential'));

            $query = new Query('/ppp/secret/print');
            $query->where('name', $pelanggan->user_pppoe);
            $secret = $client->query($query)->read();

            if (empty($secret)) {
                toast('Gagal menonaktifkan pelanggan, user tidak ditemukan di mikrotik' ,'warning');
                return redirect()->back();
            }

            // remove connection
            $query = (new Query('/ppp/active/print'))
                ->where('name', $pelanggan->user_pppoe);
            $active = $client->query($query)->read();

            if (!empty($active)) {
                $query = (new Query('/ppp/active/remove'))
                    ->equal('.id', $active[0]['.id']);
                $client->query($query)->read();
            }

            // disable secret
            $query = (new Query('/ppp/secret/set'))
                ->equal('.id', $secret[0]['.id'])
                ->equal('disabled', 'yes');

            $client->query($query)->read();
        
            toast('Langganan pelanggan berhasil dihentikan dengan alasan: ' . $request->alasan_nonaktif ,'success');
            return redirect()->back();
        
        }
    
        toast('Pelanggan sudah tidak aktif' ,'warning');
        return redirect()->back();
    }
}
