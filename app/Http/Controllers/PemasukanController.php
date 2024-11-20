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

    public function store(Request $request) {

        $validateData = $request->validate([
            'debit'             => 'required',
            'kategori'          => 'required',
            'metode_pembayaran' => 'nullable',
            'deskripsi'         => 'nullable',
        ]);

        $validateData['jenis_transaksi'] = 'Pemasukan';
        $validateData['debit'] = $request->debit;

        Transaksi::create($validateData);

        toast('Transaksi berhasil ditambah!','success');
        return redirect()->route('pemasukan.index');
    }
}
