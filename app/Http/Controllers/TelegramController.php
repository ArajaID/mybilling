<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $update = $telegram->getWebhookUpdate();

        $chatId = $update->getMessage()->getChat()->getId();
        $allowedChatIds = explode(',', env('TELEGRAM_CHAT_ID'));

        if (!in_array($chatId, $allowedChatIds)) {
            // Jika chatId tidak diizinkan, bot tidak merespons
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $text = $update->getMessage()->getText();

        // Perintah bot
        if (str_starts_with($text, '/start')) {
            $nama = $update->getMessage()->getChat()->getTitle();

            $telegram->sendMessage([
                'chat_id' => $chatId,
                'parse_mode' => 'HTML',
                'text' => "Halo! <b>"  . $nama . "</b> Anda berada di grup yang diizinkan. Gunakan perintah:\n\n" .
                            "/ceksaldo\n" .
                            "/pengeluaran [bulan] [tahun]\n" .
                            "/pemasukan [bulan] [tahun]\n" .
                            "/tagihanPelanggan\n" .
                            "/notifikasiPelanggan\n\n" .

                            "/detailPelanggan [kode_pelanggan]\n"
                            // "/tambahpengeluaran [jumlah] [kategori] [metode_pembayaran] [deskripsi]\n\n" .
  
                            // "<i>Contoh: /tambahpengeluaran 1000000 Makanan QRIS Makanan di warteg</i>"

                ]);
            } elseif (str_starts_with($text, '/ceksaldo')) {
                $this->ceksaldo($telegram, $chatId, $text);
            } elseif (str_starts_with($text, '/pengeluaran')) {
                $this->pengeluaran($telegram, $chatId, $text);
            } elseif (str_starts_with($text, '/pemasukan')) {
                $this->pemasukan($telegram, $chatId, $text);
            } elseif (str_starts_with($text, '/detailpelanggan')) {
                $this->detailpelanggan($telegram, $chatId, $text);
            } elseif(str_starts_with($text, '/tagihanPelanggan')) {
                $this->tagihanPelanggan($telegram, $chatId, $text);
            } elseif(str_starts_with($text, '/notifikasiPelanggan')) {
                $this->notifikasiPelanggan($telegram, $chatId, $text);
            } else {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Perintah tidak dikenal ⛔. Gunakan /start untuk melihat daftar perintah."
                ]);
            }

        return response()->json(['status' => 'ok']);
    }

    private function ceksaldo(Api $telegram, $chatId, $text)
    {
        $transaksi = Transaksi::all();
        $totalPemasukan = $transaksi->where('jenis_transaksi', 'Pemasukan')->sum('debit');
        $totalPengeluaran = $transaksi->where('jenis_transaksi', 'Pengeluaran')->sum('kredit');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $telegram->sendMessage([
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' => "<b>Saldo saat ini</b> : Rp. " . number_format($saldo, 0, ',', '.') . "\n\n" .
                        "<b>Total Pemasukan</b> : Rp. " . number_format($totalPemasukan, 0, ',', '.') . "\n" .
                        "<b>Total Pengeluaran</b> : Rp. " . number_format($totalPengeluaran, 0, ',', '.')
        ]);
    }

    private function pengeluaran(Api $telegram, $chatId, $text)
    {
        if (count(explode(' ', $text)) < 3) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Format tidak sesuai ⛔. Gunakan /pengeluaran [bulan] [tahun]"
            ]);
            return;
        }

        $params = explode(' ', $text);
        $bulan = $params[1];
        $tahun = $params[2];

        $transaksi = Transaksi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('jenis_transaksi', 'Pengeluaran')
            ->get();

        $totalPengeluaran = $transaksi->sum('kredit');

        $telegram->sendMessage([
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' => "<b>Total Pengeluaran Bulan " . $bulan . " Tahun " . $tahun . " </b> : Rp. " . number_format($totalPengeluaran, 0, ',', '.')
        ]);
    }

    private function pemasukan(Api $telegram, $chatId, $text)
    {
        if (count(explode(' ', $text)) < 3) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Format tidak sesuai ⛔. Gunakan /pemasukan [bulan] [tahun]"
            ]);
            return;
        }
        $params = explode(' ', $text);
        $bulan = $params[1];
        $tahun = $params[2];

        $transaksi = Transaksi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('jenis_transaksi', 'Pemasukan')
            ->get();

        $totalPemasukan = $transaksi->sum('debit');

        $telegram->sendMessage([
            'chat_id' => $chatId,
            'parse_mode' => 'HTML',
            'text' => "<b>Total Pemasukan Bulan " . $bulan . " Tahun " . $tahun . " </b> : Rp. " . number_format($totalPemasukan, 0, ',', '.')
        ]);
    }

    private function detailpelanggan(Api $telegram, $chatId, $text) {
        if (count(explode(' ', $text)) < 2) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Format tidak sesuai ⛔. Gunakan /detailpelanggan [kode_pelanggan]"
            ]);
            return;
        }

        $params = explode(' ', $text);
        $kode_pelanggan = $params[1];

        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->where('aktivasi_layanan', 1)->first();

        if ($pelanggan) {
            $noHp = $pelanggan->no_telepon ? $pelanggan->no_telepon : 'Tidak ada No HP';
            $email = $pelanggan->email ? $pelanggan->email : 'Tidak ada No Email';

            if($pelanggan->area == 'perumahan') {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'parse_mode' => 'markdown',
                    'text' => "*Detail Pelanggan 😊*\n\n" .
                              "_Kode Pelanggan_\n" . '*' . $pelanggan->kode_pelanggan . '*' . "\n" .
                              "_Nama Pelanggan_ \n " . '*' . $pelanggan->nama_pelanggan  . '*' ."\n" .
                              "_Paket Internet_ \n " . '*' . $pelanggan->paket->nama_paket . '*' ."\n" .
                              "_RT dan Blok_ \n " . '*' . $pelanggan->rt . ' - ' . $pelanggan->blok . '*' ."\n" .
                              "_Email_ \n " . '*' . $email . '*' ."\n" .
                              "_No HP_ \n " .  '*' .$noHp . '*' ."\n" .
                              "_ODC/ODP_ \n " . '*' . $pelanggan->odpData->odc->odc_induk . '/' . $pelanggan->odpData->odc->odc . '/' . $pelanggan->odpData->odp .'*' . "\n" .
                              "_Lama Berlangganan_ \n " . '*' . \Carbon\Carbon::parse($pelanggan->tanggal_aktivasi)->diffForHumans(null, true) . '*' ."\n" .
                              "_ONU_ \n " . '*' . $pelanggan->perangkat->nama_perangkat . ' - ' . $pelanggan->perangkat->sn . '*' . "\n"
                ]);
            } else {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'parse_mode' => 'markdown',
                    'text' => "*Detail Pelanggan 😊*\n\n" .
                              "_Kode Pelanggan_\n" . '*' . $pelanggan->kode_pelanggan . '*' . "\n" .
                              "_Nama Pelanggan_ \n " . '*' . $pelanggan->nama_pelanggan  . '*' ."\n" .
                              "_Paket Internet_ \n " . '*' . $pelanggan->paket->nama_paket . '*' ."\n" .
                              "_Email_ \n " . '*' . $email . '*' ."\n" .
                              "_No HP_ \n " .  '*' .$noHp . '*' ."\n" .
                              "_ODC/ODP_ \n " . '*' . $pelanggan->odpData->odc->odc_induk . '/' . $pelanggan->odpData->odc->odc . '/' . $pelanggan->odpData->odp .'*' . "\n" .
                              "_Lama Berlangganan_ \n " . '*' . \Carbon\Carbon::parse($pelanggan->tanggal_aktivasi)->diffForHumans(null, true) . '*' ."\n" .
                              "_ONU_ \n " . '*' . $pelanggan->perangkat->nama_perangkat . ' - ' . $pelanggan->perangkat->sn . '*' . "\n"
                ]);
            }
        } else {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Pelanggan tidak ditemukan."
            ]);
        }
    }

    private function tagihanPelanggan(Api $telegram, $chatId, $text) {
        $tagihan = Tagihan::where('status_pembayaran', 'BELUM-LUNAS')->get();

        if ($tagihan->count() > 0) {
            $message = "*List Tagihan Aktif 😊*\n\n";
            foreach ($tagihan as $key => $value) {
                $message .= ($key + 1) . ". [" . $value->pelanggan->kode_pelanggan . '] ' . $value->pelanggan->nama_pelanggan . " - " . $value->deskripsi . " - Rp. " . number_format($value->jumlah_tagihan, 0, ',', '.') . "\n";
            }

            $message .= "Total Tagihan : Rp. " . number_format($tagihan->sum('jumlah_tagihan'), 0, ',', '.');

            $telegram->sendMessage([
                'chat_id' => $chatId,
                'parse_mode' => 'markdown',
                'text' => $message
            ]);
        } else {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Tidak ada tagihan yang belum lunas. 😉"
            ]);
        }
    }

    private function notifikasiPelanggan(Api $telegram, $chatId, $text) {
         $dataTagihan = Tagihan::where('status_pembayaran', 'BELUM-LUNAS')->orderBy('tanggal_tagihan', 'asc')->get();

        if ($dataTagihan->count() > 0) {
            foreach($dataTagihan as $data) {
                $tagihan = "Rp. " . number_format($data->jumlah_tagihan, 0, ",", ".");
                
                $link = "https://wa.me/+62" . substr($data->pelanggan->no_telepon, 1) . "?text=Pelanggan%20Yth,%20" . $data->pelanggan->nama_pelanggan . "%20" . $data->pelanggan->kode_pelanggan .  ",%20" . $data->deskripsi . "%20Internet%20Anda%20sebesar%20" . $tagihan . "%20sudah%20terbit.%20Mohon%20segera%20melakukan%20pembayaran%20sebelum%20tanggal%2020%20agar%20layanan%20tetap%20aktif.%20Terima%20kasih,%20Aionios.NET";
                $htmlText = "Pelanggan Yth, *" . $data->pelanggan->nama_pelanggan . " (" . Str::upper($data->pelanggan->kode_pelanggan) . ")*. " . $data->deskripsi . " Internet Anda *" . $tagihan . ".* Mohon lakukan pembayaran sebelum tanggal 20 agar layanan tetap aktif. [Klik disini](" . $link . ")";
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $htmlText,
                    'parse_mode' => 'Markdown'
                ]);
            }
        } else {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Tagihan sudah lunas. 😉"
            ]);
        }
    }

    // private function tambahpengeluaran(Api $telegram, $chatId, $text)
    // {
        
    //     if (count(explode(' ', $text)) < 5) {
    //         $telegram->sendMessage([
    //             'chat_id' => $chatId,
    //             'text' => "Format tidak sesuai. Gunakan /tambahpengeluaran [tanggal] [jumlah] [kategori] [metode_pembayaran] [deskripsi]"
    //         ]);
    //         return;
    //     }

    //     $params = explode(' ', $text);
    //     $jumlah = $params[1];
    //     $kategori = $params[2];
    //     $metode_pembayaran = $params[3];
    //     $deskripsi = $params[4];

    //     $transaksi = new Transaksi();
    //     $transaksi->tanggal = now();
    //     $transaksi->kredit = $jumlah;
    //     $transaksi->kategori = $kategori;
    //     $transaksi->metode_pembayaran = $metode_pembayaran;
    //     $transaksi->deskripsi = $deskripsi;
    //     $transaksi->jenis_transaksi = 'Pengeluaran';
    //     $transaksi->save();

    //     $telegram->sendMessage([
    //         'chat_id' => $chatId,
    //         'text' => "Transaksi pengeluaran berhasil ditambahkan."
    //     ]);
    // }
}
