<?php

namespace App\Http\Controllers;

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

        $dataTagihan->update([
            'status_pembayaran' => 'Lunas'
        ]);

        $request->validate([
            'jumlah'    => 'required',
        ]);

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
