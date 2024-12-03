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
        $dataTagihan = Tagihan::with('pelanggan')->where('status_pembayaran', 'BELUM-LUNAS')->get();
        
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
        // perintah update tagihan lunas
        $dataTagihan = Tagihan::where('kode_tagihan', $request->kode_tagihan)->first();

        $bulanTagihan = 'Tagihan ' . date('M') . '-' . date('Y');

        $dataTagihan->update([
            'status_pembayaran' => 'LUNAS'
        ]);

        $request->validate([
            'jumlah'    => 'required',
        ]);

        // perintah update comment lunas di secret mikrotik
        if($dataTagihan->deskripsi == $bulanTagihan) {
            $client = new Client(config('mikrotik.credential'));

            $query = new Query('/ppp/secret/print');
            $query->where('name', $dataTagihan->pelanggan->user_pppoe);
            $secrets = $client->query($query)->read();

            foreach ($secrets as $secret) {
                // perintah remove connection active
                $queryActive = (new Query('/ppp/active/getall'))
                            ->where('name', $secret['name']);
                $response = $client->query($queryActive)->read();

                $queryRemoveActive = (new Query('/ppp/active/remove'))
                         ->equal('.id', $response[0]['.id']);
                $client->query($queryRemoveActive)->read();
                
                 // kondisi jika pelanggan terisolir
                 if($secret['profile'] == 'pelanggan-terisolir') {
                    $query = (new Query('/ppp/secret/set'))
                    ->equal('.id', $secret['.id'])
                    ->equal('profile', $dataTagihan->pelanggan->paket->bandwidth)
                    ->equal('comment', 'LUNAS');
            
                    $client->query($query)->read();
                } else {
                    $query = (new Query('/ppp/secret/set'))
                    ->equal('.id', $secret['.id'])
                    ->equal('comment', 'LUNAS');
            
                    $client->query($query)->read();
                }
            }
        }

        Transaksi::create([
            'jenis_transaksi'   => 'Pemasukan',
            'tanggal'           => $request->tanggal,
            'sumber'            => $request->kode_tagihan,
            'debit'             => $request->jumlah,
            'kategori'          => $request->deskripsi,
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);
        
        toast('Tagihan no ' . $request->kode_tagihan . ' Lunas!','success');
        return redirect()->route('tagihan.index');
    }
}
