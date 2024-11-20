<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Tagihan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index() {
        $dataTagihan = Tagihan::with('pelanggan')->get();
        
        return view('tagihan.index', [
            'tagihan' => $dataTagihan
        ]);
    }

    public function create(Request $request) {
        $dataTagihan = Tagihan::where('kode_tagihan', $request->kode_tagihan)->first();

        return view('tagihan.create', [
            'tagihan' => $dataTagihan
        ]);
    }

    public function store(Request $request) {
        $dataTagihan = Tagihan::where('kode_tagihan', $request->kode_tagihan)->first();

        $bulanTagihan = 'Tagihan ' . date('M') . '-' . date('Y');

        $dataTagihan->update([
            'status_pembayaran' => 'LUNAS'
        ]);

        $request->validate([
            'jumlah'    => 'required',
        ]);

        if($dataTagihan->deskripsi == $bulanTagihan) {
            $client = new Client(config('mikrotik.credential'));

            $query = new Query('/ppp/secret/print');
            $query->where('name', $dataTagihan->pelanggan->user_pppoe);
            $secrets = $client->query($query)->read();

            foreach ($secrets as $secret) {
                $query = (new Query('/ppp/secret/set'))
                        ->equal('.id', $secret['.id'])
                        ->equal('comment', 'LUNAS');
                
                $client->query($query)->read();
            }
        }

        Transaksi::create([
            'jenis_transaksi'   => 'Pemasukan',
            'sumber'            => $request->kode_tagihan,
            'debit'             => $request->jumlah,
            'kategori'          => $request->deskripsi,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);
        
        toast('Tagihan no ' . $request->kode_tagihan . ' Lunas!','success');
        return redirect()->route('tagihan.index');
    }
}
