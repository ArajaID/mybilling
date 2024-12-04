<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index() {
        $dataTransaksiPengeluaran = Transaksi::where('jenis_transaksi', 'Pengeluaran')->paginate(20);
        
        return view('pengeluaran.index', [
            'pengeluaran' => $dataTransaksiPengeluaran
        ]);
    }

    public function create() {
        return view('pengeluaran.create');
    }

    public function store(Request $request) {

        $validateData = $request->validate([
            'kredit'            => 'required',
            'tanggal'           => 'required',
            'kategori'          => 'required',
            'metode_pembayaran' => 'nullable',
            'deskripsi'         => 'nullable',
        ]);

        $validateData['jenis_transaksi'] = 'Pengeluaran';
        $validateData['kredit'] = $request->kredit;

        Transaksi::create($validateData);

        toast('Transaksi berhasil ditambah!','success');
        return redirect()->route('pengeluaran.index');
    }
}
