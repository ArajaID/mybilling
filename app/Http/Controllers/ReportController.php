<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\PromoPelanggan;

class ReportController extends Controller
{
      public function arusKas(Request $request) {

        $transaksi = Transaksi::query();

        // Filter berdasarkan rentang tanggal
        if ($request->has(['start_date', 'end_date'])) {
            $start_date = $request->input('start_date');
            $end_date   = $request->input('end_date');
            $transaksi  = $transaksi->whereBetween('tanggal', [$start_date, $end_date]);
        }

        $transaksi = $transaksi->get();

        $totalDebit = $transaksi->where('jenis_transaksi', 'Pemasukan')->sum('debit');
        $totalKredit = $transaksi->where('jenis_transaksi', 'Pengeluaran')->sum('kredit');

        return view('report.arus-kas', [
            'dataTransaksi' => $transaksi,
            'totalDebit'    => $totalDebit,
            'totalKredit'   => $totalKredit
        ]);
    }

    public function arusKasPDF($start_date, $end_date) {
        $transaksi = Transaksi::query();
        $transaksi = $transaksi->whereBetween('tanggal', [$start_date, $end_date]);
        $transaksi = $transaksi->get();

        $totalDebit = $transaksi->where('jenis_transaksi', 'Pemasukan')->sum('debit');
        $totalKredit = $transaksi->where('jenis_transaksi', 'Pengeluaran')->sum('kredit');

        $pdf = PDF::loadView('report.pdf.arus-kas', [
            'dataTransaksi' => $transaksi,
            'totalDebit'    => $totalDebit,
            'totalKredit'   => $totalKredit,
            'tanggalAwal'   => $start_date,
            'tanggalAkhir'  => $end_date
        ]);
        return $pdf->stream('Laporan-Data-Santri.pdf');
    }

    public function promoPelanggan() {
        $pelangganData = Pelanggan::with('promo')->where('is_active', 1)->get();

        return view('report.promo-pelanggan', [
            'pelanggan' => $pelangganData
        ]);
    }

    public function pelanggan() {
        $pelangganData = Pelanggan::with('promo')->where('is_active', 1)->paginate(20);

        return view('report.pelanggan', [
            'pelanggan' => $pelangganData
        ]);
    }
}
