<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class NotifikasiController extends Controller
{
    public function sendTelegram() {
        $dataTagihan = Tagihan::where('status_pembayaran', 'BELUM-LUNAS')->orderBy('tanggal_tagihan', 'asc')->get();

        foreach($dataTagihan as $data) {
            $tagihan = "Rp " . number_format($data->jumlah_tagihan, 0, ",", ".");

$htmlText = "Pelanggan *" . $data->pelanggan->nama_pelanggan . " (" . Str::upper($data->pelanggan->kode_pelanggan) . ")* tagihan Internet Anda *" . $tagihan . ".* Mohon lakukan pembayaran sebelum tanggal 20 agar layanan tetap aktif.";
                        Telegram::sendMessage([
                            'chat_id' => env('TELEGRAM_CHAT_ID'),
                            'text' => $htmlText,
                            'parse_mode' => 'Markdown'
                        ]);
                            
                    }
                }
}
