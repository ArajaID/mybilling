<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Paket;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $client = new Client(config('mikrotik.credential'));

        $queryPPPActive = (new Query('/ppp/active/print'));
    
        $pppActive = $client->query($queryPPPActive)->read();

        $totalPelanggan = Pelanggan::where('is_active', 1)->count();
        $tagihanActive = Tagihan::where('status_pembayaran', 'BELUM-LUNAS')->count();
        $paketActive = Paket::where('is_active', 1)->count();

        $totalDebit = Transaksi::where('jenis_transaksi', 'Pemasukan')->sum('debit');
        $totalKredit = Transaksi::where('jenis_transaksi', 'Pengeluaran')->sum('kredit');

        $saldo = $totalDebit - $totalKredit;

        return view('dashboard', [
            'pppActive'         => count($pppActive),
            'totalPelanggan'    => $totalPelanggan,
            'tagihanActive'     => $tagihanActive,
            'paketActive'       => $paketActive,
            'saldo'             => $saldo,
        ]);
    }
}
