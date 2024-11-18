<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

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
}
