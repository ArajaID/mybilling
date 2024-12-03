<?php

namespace App\Jobs;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Pelanggan;
use App\Models\PromoPelanggan;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CekBerlakuPromoJob implements ShouldQueue
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
        $client = new Client(config('mikrotik.credential'));

        $promoPelanggan = PromoPelanggan::all();

        foreach($promoPelanggan as $data) {
            $pelangganData = Pelanggan::findOrFail($data->id_pelanggan);
            $promoPelangganUpdate = PromoPelanggan::where('id_pelanggan', $pelangganData->id);

            $promoPelangganUpdate->update([
                'is_active' => 0
            ]);

            $pelangganName = $pelangganData->user_pppoe;

            // cek tanggal hari ini
            // lalu cek tanggal berakhir promo
            $tanggalBerakhir = $data->tanggal_berakhir_promo;
            $tanggalHariIni = date('Y-m-d');

            if($tanggalBerakhir == $tanggalHariIni) {
                // perintah update promo berakhir
                // perintah remove connection active
                $queryActive = (new Query('/ppp/active/print'))
                        ->where('name', $pelangganName);
                $response = $client->query($queryActive)->read();

                $query = new Query('/ppp/secret/print');
                $query->where('name', $pelangganName);
                $secret = $client->query($query)->read();

                if($response != []) {
                    $queryRemoveActive = (new Query('/ppp/active/remove'))
                                        ->equal('.id', $response[0]['.id']);
                    $client->query($queryRemoveActive)->read();

                    // jika ada active connection hapus dulu, lalu update profile
                    // perintah untuk merubah profile menjadi kembali ke paket utama
                    $query = (new Query('/ppp/secret/set'))
                        ->equal('.id', $secret[0]['.id'])
                        ->equal('profile', $pelangganData->paket->bandwidth);

                    $client->query($query)->read();
                } else {
                     // perintah untuk merubah profile menjadi kembali ke paket utama
                     $query = (new Query('/ppp/secret/set'))
                            ->equal('.id', $secret[0]['.id'])
                            ->equal('profile', $pelangganData->paket->bandwidth);

                     $client->query($query)->read();
                }
            }
        }
    }
}
