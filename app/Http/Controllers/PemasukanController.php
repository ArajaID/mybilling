<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function index() {
        $dataTransaksiPemasukan = Transaksi::where('jenis_transaksi', 'Pemasukan')->orderBy('tanggal', 'asc')->paginate(20);
        
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
            'tanggal'           => 'required',
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

    public function show(string $id)
    {
        return abort(404);
    }

    public function edit(string $id)
    {
        $pemasukan = Transaksi::findOrFail($id);

        return view('pemasukan.edit', compact('pemasukan'));
    }

    public function update(Request $request, string $id)
    {
        $pemasukan = Transaksi::findOrFail($id);

        $request->validate([
            'debit'             => 'required',
            'tanggal'           => 'required',
            'kategori'          => 'required',
            'metode_pembayaran' => 'nullable',
            'deskripsi'         => 'nullable',
        ]);

        $pemasukan->update([
            'jenis_transaksi'   => 'Pemasukan',
            'debit'             => $request->debit,
            'tanggal'           => $request->tanggal,
            'kategori'          => $request->kategori,
            'metode_pembayaran' => $request->metode_pembayaran,
            'deskripsi'         => $request->deskripsi,
        ]);

        toast('Transaksi pemasukan berhasil edit!','success');
        return redirect()->route('pemasukan.index');
    }

    public function destroy(string $id)
    {
        return abort(404);
    }
}
