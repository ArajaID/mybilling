<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function index() {
        $dataTransaksiPemasukan = Transaksi::where('jenis_transaksi', 'Pemasukan')->get();
        
        return view('pemasukan.index', [
            'pemasukan' => $dataTransaksiPemasukan
        ]);
    }

    public function create() {
        return view('pemasukan.create');
    }
}
