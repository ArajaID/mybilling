<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use RouterOS\Query;
use RouterOS\Client;
use App\Models\Paket;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;

class TagihanController extends Controller
{
    public function index() {
        $dataTagihan = Tagihan::with('pelanggan')->where('status_pembayaran', 'BELUM-LUNAS')->get();
        
        return view('tagihan.index', [
            'tagihan' => $dataTagihan,
            'totalTagihan' => $dataTagihan->sum('jumlah_tagihan'),
        ]);
    }

    public function create(Request $request) {
        $dataTagihan = Tagihan::where('kode_tagihan', $request->kode_tagihan)->first();

        return view('tagihan.create', [
            'tagihan' => $dataTagihan
        ]);
    }

    public function store(Request $request) {
        DB::transaction(function() use ($request) {
            // perintah update tagihan lunas
            $dataTagihan = Tagihan::where('kode_tagihan', $request->kode_tagihan)->first();
            // cek apakah pelanggan sedang menggunakan promo
            $pelanggan = Pelanggan::findOrFail($dataTagihan->id_pelanggan);
            // cek paket promo yang sedang dipakai
            $paket = Paket::where('id', $pelanggan->promo[0]->tambahan_speed)->first();

            $bulanTagihan = 'Tagihan ' . date('M') . '-' . date('Y');

            $dataTagihan->update([
                'status_pembayaran' => 'LUNAS'
            ]);

            $request->validate([
                'jumlah'    => 'required',
            ]);

            // perintah update comment lunas di secret mikrotik
            if($dataTagihan->deskripsi == $bulanTagihan) {
                $client = new Client(config('mikrotik.credential'));

                $query = new Query('/ppp/secret/print');
                $query->where('name', $dataTagihan->pelanggan->user_pppoe);
                $secrets = $client->query($query)->read();

                foreach ($secrets as $secret) {
                    // perintah remove connection active
                    $queryActive = (new Query('/ppp/active/getall'))
                                ->where('name', $secret['name']);
                    $response = $client->query($queryActive)->read();

                    $queryRemoveActive = (new Query('/ppp/active/remove'))
                            ->equal('.id', $response[0]['.id']);
                    $client->query($queryRemoveActive)->read();
                    
                    // kondisi jika pelanggan terisolir
                    if($secret['profile'] == 'pelanggan-terisolir') {
                        
                        // cek pelanggan menggunakan promo atau tidak
                        if($pelanggan->promo[0]->tambahan_speed) {
                            $dataPaket = $paket->bandwidth;
                        } else {
                            $dataPaket = $dataTagihan->pelanggan->paket->bandwidth;
                        }

                        $query = (new Query('/ppp/secret/set'))
                        ->equal('.id', $secret['.id'])
                        ->equal('profile', $dataPaket)
                        ->equal('comment', 'LUNAS');
                
                        $client->query($query)->read();
                    } else {
                        $query = (new Query('/ppp/secret/set'))
                        ->equal('.id', $secret['.id'])
                        ->equal('comment', 'LUNAS');
                
                        $client->query($query)->read();
                    }
                }
            }
            
            $deskripsiTransaksi = $request->kode_pelanggan . ' - ' . $request->nama_pelanggan;
            $metodePembayaran = $request->metode_pembayaran;
            $jumlahDebit = $request->jumlah;

            // perhitungan uang masuk ke tb transaksi
            Transaksi::create([
                'jenis_transaksi'   => 'Pemasukan',
                'tanggal'           => $request->tanggal,
                'sumber'            => $request->kode_tagihan,
                'debit'             => $jumlahDebit,
                'kategori'          => $request->deskripsi,
                'metode_pembayaran' => $metodePembayaran,
                'deskripsi'         => $deskripsiTransaksi
            ]);

            // perhitungan biaya admin penerimaan QRIS
            if($metodePembayaran == "QRIS") {
                if($jumlahDebit < 100000) {
                    $biayaAdmin = $jumlahDebit;
                } else {
                    $potongan = 0.3;
                    $biayaAdmin = ($potongan/100) * $jumlahDebit;
                }
            } else {
                $biayaAdmin = $jumlahDebit;
            }

            $deskripsiBiayaAdmin = 'Admin Bank Penerimaan ' . $request->deskripsi . ' ' . $deskripsiTransaksi ;
            Transaksi::create([
                'jenis_transaksi'   => 'Pengeluaran',
                'tanggal'           => $request->tanggal,
                'sumber'            => $request->kode_tagihan,
                'kredit'            => $biayaAdmin,
                'kategori'          => 'Admin Bank',
                'metode_pembayaran' => $metodePembayaran,
                'deskripsi'         => $deskripsiBiayaAdmin
            ]);

            $debit = 'Rp. ' . number_format($request->jumlah, 0, ',', '.');
            $tanggalTransaksi = Carbon::parse($request->tanggal)->format('d-m-Y');
            $tanggalBuat = Carbon::parse($request->created_at)->format('d-m-Y H:i:s');

            $saldo = 0;
            $transaksiAll = Transaksi::all();
            foreach($transaksiAll as $data) {
                $saldo += $data->debit - $data->kredit;
            }
            $formatSaldo = 'Rp. ' . number_format($saldo, 0, ',', '.');
            $biayaAdminFormat = 'Rp. ' . number_format($biayaAdmin, 0, ',', '.');
            

$htmlText = "*TRANSAKSI UANG MASUK* 🔽";
$htmlText .= "
✅ *BERHASIL*";
$htmlText .= "
`===========================`
`Tanggal   :` $tanggalTransaksi
`Kategori  :` $request->deskripsi
`Deskripsi :` $deskripsiTransaksi
                                    
`Jumlah    :` *$debit*
`===========================`
`Saldo     :` *$formatSaldo*
`===========================`
`Dibuat    :` $tanggalBuat
`===========================`
";
                        Telegram::sendMessage([
                            'chat_id' => env('TELEGRAM_CHAT_ID'),
                            'text' => $htmlText,
                            'parse_mode' => 'Markdown'
                        ]);
            

$htmlTextKeluar = "*TRANSAKSI UANG KELUAR* 🔼";
$htmlTextKeluar .= "
✅ *BERHASIL*";
$htmlTextKeluar .= "
`===========================`
`Tanggal   :` $tanggalTransaksi
`Kategori  :` Admin Bank
`Deskripsi :` $deskripsiBiayaAdmin

`Jumlah    :` *$biayaAdminFormat*
`===========================`
`Saldo     :` *$formatSaldo*
`===========================`
`Dibuat    :` $tanggalBuat
`===========================`
";
                        Telegram::sendMessage([
                            'chat_id' => env('TELEGRAM_CHAT_ID'),
                            'text' => $htmlTextKeluar,
                            'parse_mode' => 'Markdown'
                        ]);
                    
                    });


        toast('Tagihan no ' . $request->kode_tagihan . ' Lunas!','success');
        return redirect()->route('tagihan.index');
    }
}
