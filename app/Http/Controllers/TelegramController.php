<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use Telegram\Bot\Api;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Support\Str;
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

                            "/detailPelanggan [kode_pelanggan]\n\n" .

                            "/buatLinkRemoteONT [kode_pelanggan]\n" .
                            "/hapusLinkRemoteONT [kode_pelanggan]\n"
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
            } elseif(str_starts_with($text, '/buatLinkRemoteONT')) {
                $this->buatLinkRemoteONT($telegram, $chatId, $text);
            } elseif(str_starts_with($text, '/hapusLinkRemoteONT')) {
                $this->hapusLinkRemoteONT($telegram, $chatId, $text);
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

    private function buatLinkRemoteONT(Api $telegram, $chatId, $text) {
        $params = explode(' ', $text);
        $kode_pelanggan = $params[1];

        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->where('aktivasi_layanan', 1)->first();
        // Cek apakah pelanggan ditemukan
        if (!$pelanggan) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Pelanggan tidak ditemukan ☹"
            ]);
            return;
        }

        $userPPPoE = $pelanggan->user_pppoe;

        $client = new Client(config('mikrotik.credential'));

        $queryAddress = new Query('/ppp/active/print');
        $queryAddress->where('name', $userPPPoE);
        $pppActive = $client->query($queryAddress)->read();

        // Cek apakah ada koneksi PPP aktif
        if(count($pppActive) === 0) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Tidak ada koneksi PPPoE aktif. Pastikan router pelanggan terhubung."
            ]);
            return;
        }

        $port = rand(1000, 9999);

        $query =
            (new Query('/ip/firewall/nat/add'))
                ->equal('chain', 'dstnat')
                ->equal('dst-address', '10.22.30.4')
                ->equal('protocol', 'tcp')
                ->equal('dst-port', $port)
                ->equal('in-interface', 'ovpn-cendol-dawet')
                ->equal('action', 'dst-nat')
                ->equal('to-ports', '80')
                ->equal('to-addresses', $pppActive[0]['address'])
                ->equal('comment', $userPPPoE);

        // Send query and read response from RouterOS (ordinary answer from update/create/delete queries has empty body)
        $client->query($query)->read();

        $linkRemote = 'Link remote ONT untuk pelanggan ' . $userPPPoE . ' telah dibuat. Silakan akses melalui link berikut ' . 'https://tunnel.araja.my.id:' . $port;

        $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $linkRemote
        ]);
    }

    private function hapusLinkRemoteONT(Api $telegram, $chatId, $text) {
        $params = explode(' ', $text);
        $kode_pelanggan = $params[1];

        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->where('aktivasi_layanan', 1)->first();
        // Cek apakah pelanggan ditemukan
        if (!$pelanggan) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Pelanggan tidak ditemukan ☹"
            ]);
            return;
        }

        $userPPPoE = 'router-mikrotik-server';

        $client = new Client(config('mikrotik.credential'));

        $queryFirewallNat = (new Query('/ip/firewall/nat/print'))
                        ->where('comment', $userPPPoE);
        $response = $client->query($queryFirewallNat)->read();

        if(count($response) === 0) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "Tidak ada link remote ONT yang ditemukan untuk pelanggan " . $userPPPoE
            ]);
            return;
        }

        $queryRemoveNat = (new Query('/ip/firewall/nat/remove'))
                            ->equal('.id', $response[0]['.id']);
        $client->query($queryRemoveNat)->read();

         $messsageRemove = 'Link remote ONT untuk pelanggan ' . $userPPPoE . ' Berhasil dihapus.';

        $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $messsageRemove
        ]);
    }
}
