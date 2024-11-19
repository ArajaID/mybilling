<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Promo;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\PromoPelanggan;
use Illuminate\Support\Facades\DB;

class AktivasiController extends Controller
{
    public function create() {
        $kodePelanggan = request()->kode_pelanggan;

        $queryPelanggan = Pelanggan::where('kode_pelanggan', $kodePelanggan)->where('is_active', 1)->where('aktivasi_layanan', 0)->first();
        $daftarPromo = Promo::where('status_promo', 1)->get();

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

            $comment = $dataPelanggan->kode_pelanggan . ' | ' . $dataPelanggan->nama_pelanggan;

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
                    ->equal('profile', 'PPP-DIREKTUR')
                    ->equal('local_address', '172.16.10.1')
                    ->equal('comment', $comment);

            // Send query and read response from RouterOS (ordinary answer from update/create/delete queries has empty body)
            $client->query($query)->read();

            toast('Aktivasi berhasil!','success');
            return redirect()->route('pelanggan.index');
        });

         /**
            * Update status aktivasi_layanan menjadi true dan tanggal_aktivasi di tb_pelanggan.
            * Insert data promo ke tb_promopelanggan
            * Insert user_pppoe dan password_pppoe ke MikroTik
            * Insert data biaya pemasangan baru ke tb_keuangan
        */
    }
}
