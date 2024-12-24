<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
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
                'message' => 'Unauthorized group',
            ], 403);
        }

        $text = $update->getMessage()->getText();

        // Perintah bot
        if (str_starts_with($text, '/start')) {
            $nama = $update->getMessage()->getChat()->getFirstName();

            $telegram->sendMessage([
                'chat_id' => $chatId,
                'parse_mode' => 'HTML',
                'text' => "Halo! <b>"  . $nama . "</b> Anda berada di grup yang diizinkan. Gunakan perintah:\n\n" .
                            "/cektransaksi\n" .
                            "/pengeluaran [bulan] [tahun]\n" .
                            "/pemasukan [bulan] [tahun]\n"
                            // "/tambahpengeluaran [jumlah] [kategori] [metode_pembayaran] [deskripsi]\n\n" .
  
                            // "<i>Contoh: /tambahpengeluaran 1000000 Makanan QRIS Makanan di warteg</i>"

                ]);
            } elseif (str_starts_with($text, '/cektransaksi')) {
                $this->cektransaksi($telegram, $chatId, $text);
            } elseif (str_starts_with($text, '/pengeluaran')) {
                $this->pengeluaran($telegram, $chatId, $text);
            } elseif (str_starts_with($text, '/pemasukan')) {
                $this->pemasukan($telegram, $chatId, $text);
            } else {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Perintah tidak dikenal. Gunakan /start untuk melihat daftar perintah."
                ]);
            }

        return response()->json(['status' => 'ok']);
    }

    private function cektransaksi(Api $telegram, $chatId, $text)
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
                'text' => "Format tidak sesuai. Gunakan /pengeluaran [bulan] [tahun]"
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
                'text' => "Format tidak sesuai. Gunakan /pemasukan [bulan] [tahun]"
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
