<?php

namespace App\Jobs;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateTagihan implements ShouldQueue
{
    use Queueable;

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

            $tagihan = new Tagihan;
            $tagihan->kode_tagihan      = $kodeTagihan;
            $tagihan->id_pelanggan      = $pelanggan->id;
            $tagihan->tanggal_tagihan   = now();
            $tagihan->jumlah_tagihan    = $pelanggan->paket->harga;
            $tagihan->status_pembayaran = 'BELUM-LUNAS';
            $tagihan->deskripsi         = $desc;
            $tagihan->save();
        }

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
