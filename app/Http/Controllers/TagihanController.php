<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Paket;
use App\Models\Tagihan;
use App\Models\Pelanggan;
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
        // cek apakah pelanggan sedang menggunakan promo
        $pelanggan = Pelanggan::findOrFail($dataTagihan->id_pelanggan);
        // cek paket promo yang sedang dipakai
        $paket = Paket::findOrFail($pelanggan->promo[0]->tambahan_speed);

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
                    
                    // cek pelanggan menggunakan promo atau tidak
                    if($pelanggan->promo[0]->tambahan_speed) {
                        $dataPaket = $paket->bandwidth;
                    } else {
                        $dataPaket = $dataTagihan->pelanggan->paket->bandwidth;
                    }

                    $query = (new Query('/ppp/secret/set'))
                    ->equal('.id', $secret['.id'])
                    ->equal('profile', $dataPaket)
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
        
        $deskripsiTransaksi = $request->kode_pelanggan . ' - ' . $request->nama_pelanggan;

        Transaksi::create([
            'jenis_transaksi'   => 'Pemasukan',
            'tanggal'           => $request->tanggal,
            'sumber'            => $request->kode_tagihan,
            'debit'             => $request->jumlah,
            'kategori'          => $request->deskripsi,
            'metode_pembayaran' => $request->metode_pembayaran,
            'deskripsi'         => $deskripsiTransaksi
        ]);
        
        toast('Tagihan no ' . $request->kode_tagihan . ' Lunas!','success');
        return redirect()->route('tagihan.index');
    }
}
