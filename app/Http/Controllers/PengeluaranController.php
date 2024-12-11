<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index() {
        $dataTransaksiPengeluaran = Transaksi::where('jenis_transaksi', 'Pengeluaran')->orderBy('tanggal', 'desc')->paginate(20);
        
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

        toast('Transaksi pengeluaran berhasil ditambah!','success');
        return redirect()->route('pengeluaran.index');
    }

    public function show(string $id)
    {
        return abort(404);
    }

    public function edit(string $id)
    {
        $pengeluaran = Transaksi::findOrFail($id);

        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, string $id)
    {
        $pengeluaran = Transaksi::findOrFail($id);

        $request->validate([
            'kredit'            => 'required',
            'tanggal'           => 'required',
            'kategori'          => 'required',
            'metode_pembayaran' => 'nullable',
            'deskripsi'         => 'nullable',
        ]);

        $pengeluaran->update([
            'jenis_transaksi'   => 'Pengeluaran',
            'kredit'            => $request->kredit,
            'tanggal'           => $request->tanggal,
            'kategori'          => $request->kategori,
            'metode_pembayaran' => $request->metode_pembayaran,
            'deskripsi'         => $request->deskripsi,
        ]);

        toast('Transaksi pengeluaran berhasil edit!','success');
        return redirect()->route('pengeluaran.index');
    }

    public function destroy(string $id)
    {
        return abort(404);
    }
}
