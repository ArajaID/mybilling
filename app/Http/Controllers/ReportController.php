<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\PromoPelanggan;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
      public function arusKas(Request $request) {
        $transaksi = Transaksi::query();

        // Filter berdasarkan rentang tanggal
        $start_date = $request->input('start_date', null);
        $end_date   = $request->input('end_date', null);

        // Pastikan $start_date dan $end_date memiliki nilai
        if ($start_date && $end_date) {
            $transaksi = $transaksi->whereBetween('tanggal', [$start_date, $end_date]);
        }
        
        $transaksi = $transaksi->orderBy('tanggal', 'asc')->get();

        $totalDebit = $transaksi->where('jenis_transaksi', 'Pemasukan')->sum('debit');
        $totalKredit = $transaksi->where('jenis_transaksi', 'Pengeluaran')->sum('kredit');

        // Hitung saldo bulan sebelumnya
        $saldoBulanSebelumnya = 0;

        // buatkan saldo bulan sebelumnya
        if($start_date) {
            $saldo = Transaksi::where('tanggal', '<', $start_date)->get();
            $saldoBulanSebelumnya = 0;
            foreach ($saldo as $item) {
                if ($item->jenis_transaksi == 'Pemasukan') {
                    $saldoBulanSebelumnya += $item->debit;
                } else {
                    $saldoBulanSebelumnya -= $item->kredit;
                }
            }
        }
        
        // tambahkan saldo bulan sebelumnya ke total debit
        $totalDebit += $saldoBulanSebelumnya;

        return view('report.arus-kas', [
            'dataTransaksi' => $transaksi,
            'totalDebit'    => $totalDebit,
            'totalKredit'   => $totalKredit,
            'saldoBulanSebelumnya' => $saldoBulanSebelumnya,
        ]);
    }

    public function arusKasPDF($start_date, $end_date) {
        $transaksi = Transaksi::query();
        $transaksi = $transaksi->whereBetween('tanggal', [$start_date, $end_date]);
        $transaksi = $transaksi->orderBy('tanggal', 'asc')->get();

        $totalDebit = $transaksi->where('jenis_transaksi', 'Pemasukan')->sum('debit');
        $totalKredit = $transaksi->where('jenis_transaksi', 'Pengeluaran')->sum('kredit');

        // buatkan saldo bulan sebelumnya
        $saldo = Transaksi::where('tanggal', '<', $start_date)->get();
        $saldoBulanSebelumnya = 0;
        foreach ($saldo as $item) {
            if ($item->jenis_transaksi == 'Pemasukan') {
                $saldoBulanSebelumnya += $item->debit;
            } else {
                $saldoBulanSebelumnya -= $item->kredit;
            }
        }
        // tambahkan saldo bulan sebelumnya ke total debit
        $totalDebit += $saldoBulanSebelumnya;

        $pdf = PDF::loadView('report.pdf.arus-kas', [
            'dataTransaksi' => $transaksi,
            'totalDebit'    => $totalDebit,
            'totalKredit'   => $totalKredit,
            'tanggalAwal'   => $start_date,
            'tanggalAkhir'  => $end_date,
            'saldoBulanSebelumnya' => $saldoBulanSebelumnya,
        ]);

        $fileName = 'Laporan-Arus-Kas-' . $start_date . '-' . $end_date . '.pdf';
        return $pdf->stream($fileName);
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
