<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TutupBukuController extends Controller
{
    public function index(Request $request) {
        $transaksi = Transaksi::query();

        // Filter berdasarkan rentang tanggal
        $bulan = $request->input('bulan', null);
        $tahun = $request->input('tahun', null);

        // Pastikan $start_date dan $end_date memiliki nilai
        if ($bulan && $tahun) {
            $transaksi = $transaksi->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }
        
        $transaksi = $transaksi->orderBy('tanggal', 'asc')->get();

        $totalDebit = $transaksi->where('jenis_transaksi', 'Pemasukan')->sum('debit');
        $totalKredit = $transaksi->where('jenis_transaksi', 'Pengeluaran')->sum('kredit');

        // Hitung saldo bulan sebelumnya
        $saldoBulanSebelumnya = 0;

        // buatkan saldo bulan sebelumnya
        if($bulan) {
            $saldo = Transaksi::whereMonth('tanggal', '<', $bulan)->get();
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

        return view('tutup-buku.index', [
            'dataTransaksi' => $transaksi,
            'totalDebit'    => $totalDebit,
            'totalKredit'   => $totalKredit,
            'saldoBulanSebelumnya' => $saldoBulanSebelumnya,
        ]);
    }

    public function isPosted($bulan, $tahun) {
        $transaksi = Transaksi::query();
        $transaksi = $transaksi->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);

        $transaksi->update([
            'is_posted' => 1,
            'posted_at' => now()
        ]);
        
        toast('Data berhasil ditutup!','success');
        return redirect()->route('tutupbuku.index');
    }
}
