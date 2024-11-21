<?php

namespace App\Jobs;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Silvanix\Wablas\Message;
use App\Helpers\FinanceHelper;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateTagihan implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $desc = 'Tagihan ' . date('M') . '-' . date('Y');

        $dataPelanggan = Pelanggan::where('is_active', 1)->where('aktivasi_layanan', 1)->get();

        foreach ($dataPelanggan as $pelanggan) {
            $kodeTagihan = 'INV-' . date('y') . date('m') . date('d') . mt_rand(1000, 9999);
           
            $startDate = $pelanggan->tanggal_aktivasi;
            $monthlyFee = $pelanggan->paket->harga;
            $periodeBulan = now()->startOfMonth()->format('Y-m-d');
            $excludedDescription = "Biaya Pasang Baru";

            // Cek apakah tagihan sudah ada untuk periode ini
            if (FinanceHelper::tagihanExists($pelanggan->id, $periodeBulan, $excludedDescription)) {
                continue; // Lewati jika sudah ada tagihan
            }

            $jumlahTagihan = FinanceHelper::calculateTagihan($startDate, $monthlyFee);

            // Simpan tagihan
            Tagihan::create([
                'kode_tagihan'      => $kodeTagihan,
                'id_pelanggan'      => $pelanggan->id,
                'tanggal_tagihan'   => $startDate,
                'periode_bulan'     => now()->startOfMonth()->format('Y-m-d'),
                'status_pembayaran' => 'BELUM-LUNAS',
                'deskripsi'         => $desc,
                'start_date'        => $startDate,
                'jumlah_tagihan'    => $jumlahTagihan,
            ]);

             // Perintah mengirim pemberitahuan ke WA pelanggan

            $send = new Message();

            $tagihanRupiah = number_format($jumlahTagihan,  2, ",", ".");

            $phones     = $pelanggan->no_telepon;
            $message    = 'Pelanggan, *' . $pelanggan->nama_pelanggan . '* tagihan internet anda Rp. ' . $tagihanRupiah . '. Mohon lakukan pembayaran melalui QRIS agar layanan tetap aktif.';
       
            $send->single_text($phones,$message);

        }

        // Perintah update Comment di MikroTik
        $client = new Client(config('mikrotik.credential'));

        $query = new Query('/ppp/secret/print');
        $query->where('comment', 'LUNAS');
        $secrets = $client->query($query)->read();
        
        foreach ($secrets as $secret) {
            $query = (new Query('/ppp/secret/set'))
                ->equal('.id', $secret['.id'])
                ->equal('comment', 'BELUM-LUNAS');
        
            $client->query($query)->read();
        } 
    }
}
