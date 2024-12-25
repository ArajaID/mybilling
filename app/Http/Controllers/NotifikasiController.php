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
            $tagihan = "Rp. " . number_format($data->jumlah_tagihan, 0, ",", ".");
            
            $link = "https://wa.me/62" . substr($data->pelanggan->no_telepon, 1) . "?text=Pelanggan%20Yth,%20" . $data->pelanggan->nama_pelanggan . "%20" . $data->pelanggan->kode_pelanggan .  ",%20" . $data->deskripsi . "%20Internet%20Anda%20sebesar%20" . $tagihan . "%20sudah%20terbit.%20Mohon%20segera%20melakukan%20pembayaran%20sebelum%20tanggal%2020%20agar%20layanan%20tetap%20aktif.%20Terima%20kasih,%20Aionios.NET";
            $htmlText = "Pelanggan Yth, *" . $data->pelanggan->nama_pelanggan . " (" . Str::upper($data->pelanggan->kode_pelanggan) . ")*. " . $data->deskripsi . " Internet Anda *" . $tagihan . ".* Mohon lakukan pembayaran sebelum tanggal 20 agar layanan tetap aktif. [Klik disini](" . $link . ")";
            Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => $htmlText,
                'parse_mode' => 'Markdown'
            ]);
                            
                    }
                }
}
