<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function arusKas(Request $request) {
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $transaksi = Transaksi::orderBy('created_at', 'asc')->get();
        $totalDebit = Transaksi::where('jenis_transaksi', 'Pemasukan')->sum('debit');
        $totalKredit = Transaksi::where('jenis_transaksi', 'Pengeluaran')->sum('kredit');

        return view('report.arus-kas', [
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'dataTransaksi' => $transaksi,
            'totalDebit'    => $totalDebit,
            'totalKredit'   => $totalKredit
        ]);
    }
}
