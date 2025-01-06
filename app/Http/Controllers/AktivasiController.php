<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use RouterOS\Query;
use RouterOS\Client;
use App\Models\Paket;
use App\Models\Promo;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Perangkat;
use Illuminate\Http\Request;
use App\Models\PromoPelanggan;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;

class AktivasiController extends Controller
{
    public function create() {
        $kodePelanggan = request()->kode_pelanggan;

        $queryPelanggan = Pelanggan::where('kode_pelanggan', $kodePelanggan)->where('is_active', 1)->where('aktivasi_layanan', 0)->first();
        $daftarPromo = Promo::where('status_promo', 1)->where('tanggal_berakhir', '>', now())->get();
        // query perangkat yang belum terpakai pelanggan lain
        $daftarPerangkat = Perangkat::whereDoesntHave('pelanggan', function($query) {
            $query->where('aktivasi_layanan', 1);
        })->get();

        if($queryPelanggan) {
            return view('aktivasi.create', [
                'dataPelanggan' => $queryPelanggan,
                'kodePelanggan' => $kodePelanggan,
                'daftarPromo' => $daftarPromo,
                'daftarPerangkat' => $daftarPerangkat
            ]);
        } else {
            if($kodePelanggan) {
                alert()->error('Oops','Kode pelanggan ' . $kodePelanggan . ' sudah diaktivasi atau belum terdaftar didalam sistem')->persistent(true);
            }
            return view('aktivasi.create', [
                'dataPelanggan' => 0,
                'kodePelanggan' => $kodePelanggan,
                'daftarPromo' => $daftarPromo,
                'daftarPerangkat' => $daftarPerangkat
            ]);
        }
    }

    public function store(Request $request) {
        DB::transaction(function () use ($request) {
            // validasi form id_perangkat required
            $request->validate([
                'id_perangkat' => 'required'
            ]);
            // inisialisasi id pelanggan dan tanggal klaim
            $idPelanggan = $request->id_pelanggan;
            $klaimTgl    = $request->tanggal_klaim;
            // data pelanggan
            $dataPelanggan = Pelanggan::findOrFail($idPelanggan);
            // cari data promo berdasarkan id promo
            $promo = Promo::findOrFail($request->id_promo);

            // Promo Diskon Biaya Pemasangan
            $biayaPemasangan = 300000;

            if($promo->diskon) {
                $tagihanDiskon = $biayaPemasangan - $promo->diskon;
            } else {
                $tagihanDiskon = $biayaPemasangan;
            }
            
            // Promo Upgrade Speed
            if($promo->tambahan_speed) {
                $dataPaket = Paket::findOrFail($promo->tambahan_speed);
                $bwPromo = $dataPaket->bandwidth;
            } else {
                $bwPromo = $dataPelanggan->paket->bandwidth;
            }

            // update data pelanggan filed aktivasi layanan
            $dataPelanggan->update([
                'aktivasi_layanan' => 1,
                'tanggal_aktivasi' => $klaimTgl,
                'id_perangkat'     => $request->id_perangkat
            ]);

            // logika untuk berlaku bulan upgrade speed
            $intBeralkuBulan = (int)$request->berlaku_bulan;

            $tanggalBerakhir = Carbon::parse($klaimTgl)->addMonths($intBeralkuBulan)->format('Y-m-d');
            
            // create data promo pelanggan
            PromoPelanggan::create([
                'id_pelanggan'  => $idPelanggan,
                'id_promo'      => $request->id_promo,
                'tanggal_klaim' => $klaimTgl,
                'berlaku_bulan' => $request->berlaku_bulan,
                'tanggal_berakhir_promo' => $tanggalBerakhir
            ]);


            // membuat user secret di mikrotik
            $client = new Client(config('mikrotik.credential'));

            // Send "equal" query with details about IP address which should be created
            $query =
                (new Query('/ppp/secret/add'))
                    ->equal('name', $dataPelanggan->user_pppoe)
                    ->equal('password', $dataPelanggan->password_pppoe)
                    ->equal('service', 'pppoe')
                    ->equal('profile', $bwPromo)
                    ->equal('comment', 'LUNAS');

            // Send query and read response from RouterOS (ordinary answer from update/create/delete queries has empty body)
            $client->query($query)->read();

            $kodeTagihan = 'INV-' . date('y') . date('m') . date('d') . mt_rand(1000, 9999);

            Tagihan::create([
                'kode_tagihan'      => $kodeTagihan,
                'id_pelanggan'      => $idPelanggan,
                'tanggal_tagihan'   => now(),
                'jumlah_tagihan'    => $tagihanDiskon,
                'periode_bulan'     => now(),
                'status_pembayaran' => 'BELUM-LUNAS',
                'deskripsi'         => 'Biaya Pasang Baru'
            ]);

            $formatTanggal = Carbon::parse($dataPelanggan->tanggal_aktivasi)->format('d-m-Y');

$htmlText = "`==============================`
";
$htmlText .= "🚀 *AKTIVASI PELANGGAN BERHASIL ✅* 🚀";
$htmlText .= "
`==============================`
`Tgl Aktivasi   :` $formatTanggal
`Kode Pelanggan :` *$dataPelanggan->kode_pelanggan*
`Nama Pelanggan :` *$dataPelanggan->nama_pelanggan*
";
$htmlText .= "`Paket          :` " . $dataPelanggan->paket->nama_paket;
$htmlText .= "

`User PPPoE     :` *$dataPelanggan->user_pppoe*
`Password PPPoE :` *$dataPelanggan->password_pppoe*
`==============================`
_Silahakan masukkan user dan password PPPoE ke ONT Pelanggan

Terima Kasih 😉_
";
                                    Telegram::sendMessage([
                                        'chat_id' => env('TELEGRAM_CHAT_ID'),
                                        'text' => $htmlText,
                                        'parse_mode' => 'Markdown'
                                    ]);
        });



         /**
            * Update status aktivasi_layanan menjadi true dan tanggal_aktivasi di tb_pelanggan.
            * Insert data promo ke tb_promopelanggan
            * Insert user_pppoe dan password_pppoe ke MikroTik
            * Insert data biaya pemasangan baru ke tb_keuangan
            * cek promo berakhir menggunakan scheduler
        */

        toast('Aktivasi berhasil!','success');
            return redirect()->route('pelanggan.index');
    }

    public function pelangganOnline() {
        $client = new Client(config('mikrotik.credential'));

        $queryPPPActive = (new Query('/ppp/active/print'));
    
        $pppActive = $client->query($queryPPPActive)->read();

        $dataPelanggan = Pelanggan::all();

        return view('aktivasi.pelanggan-online', [
            'totalOnline' => count($pppActive),
            'pppActive' => $pppActive,
            'pelanggan' => $dataPelanggan
        ]);
    }
}
