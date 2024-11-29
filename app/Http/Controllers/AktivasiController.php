<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Promo;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\PromoPelanggan;
use Illuminate\Support\Facades\DB;

class AktivasiController extends Controller
{
    public function create() {
        $kodePelanggan = request()->kode_pelanggan;

        $queryPelanggan = Pelanggan::where('kode_pelanggan', $kodePelanggan)->where('is_active', 1)->where('aktivasi_layanan', 0)->first();
        $daftarPromo = Promo::where('status_promo', 1)->where('tanggal_berakhir', '>', now())->get();

        if($queryPelanggan) {
            return view('aktivasi.create', [
                'dataPelanggan' => $queryPelanggan,
                'kodePelanggan' => $kodePelanggan,
                'daftarPromo' => $daftarPromo
            ]);
        } else {
            if($kodePelanggan) {
                alert()->error('Oops','Kode pelanggan ' . $kodePelanggan . ' sudah diaktivasi atau belum terdaftar didalam sistem')->persistent(true);
            }
            return view('aktivasi.create', [
                'dataPelanggan' => 0,
                'kodePelanggan' => $kodePelanggan,
                'daftarPromo' => $daftarPromo,
            ]);
        }
    }

    public function store(Request $request) {
        DB::transaction(function () use ($request) {
            $idPelanggan = $request->id_pelanggan;
            $dataPelanggan = Pelanggan::findOrFail($idPelanggan);
            $promo = Promo::findOrFail($request->id_promo);

            // Promo Diskon Biaya Pemasangan
            $biayaPemasangan = 300000;

            if($promo->diskon) {
                $tagihanDiskon = $biayaPemasangan - $promo->diskon;
            } else {
                $tagihanDiskon = $biayaPemasangan;
            }
            // Promo Upgrade Speed

            $dataPelanggan->update([
                'aktivasi_layanan' => 1,
                'tanggal_aktivasi' => $request->tanggal_klaim
            ]);

            PromoPelanggan::create([
                'id_pelanggan'  => $idPelanggan,
                'id_promo'      => $request->id_promo,
                'tanggal_klaim' => $request->tanggal_klaim,
                'berlaku_bulan' => $request->berlaku_bulan
            ]);

            $client = new Client(config('mikrotik.credential'));

            // Send "equal" query with details about IP address which should be created
            $query =
                (new Query('/ppp/secret/add'))
                    ->equal('name', $dataPelanggan->user_pppoe)
                    ->equal('password', $dataPelanggan->password_pppoe)
                    ->equal('service', 'pppoe')
                    ->equal('profile', $dataPelanggan->paket->bandwidth)
                    ->equal('comment', 'LUNAS');

            // Send query and read response from RouterOS (ordinary answer from update/create/delete queries has empty body)
            $client->query($query)->read();

            $kodeTagihan = 'INV-' . date('y') . date('m') . date('d') . mt_rand(1000, 9999);

            Tagihan::create([
                'kode_tagihan'      => $kodeTagihan,
                'id_pelanggan'      => $idPelanggan,
                'tanggal_tagihan'   => now(),
                'jumlah_tagihan'    => $tagihanDiskon,
                'periode_bulan'     => now(),
                'status_pembayaran' => 'BELUM-LUNAS',
                'deskripsi'         => 'Biaya Pasang Baru'
            ]);
        });

         /**
            * Update status aktivasi_layanan menjadi true dan tanggal_aktivasi di tb_pelanggan.
            * Insert data promo ke tb_promopelanggan
            * Insert user_pppoe dan password_pppoe ke MikroTik
            * Insert data biaya pemasangan baru ke tb_keuangan
        */

        toast('Aktivasi berhasil!','success');
            return redirect()->route('pelanggan.index');
    }

    public function pelangganOnline() {
        $client = new Client(config('mikrotik.credential'));

        $queryPPPActive = (new Query('/ppp/active/print'));
    
        $pppActive = $client->query($queryPPPActive)->read();

        return view('aktivasi.pelanggan-online', [
            'totalOnline' => count($pppActive),
            'pppActive' => $pppActive
        ]);
    }
}
