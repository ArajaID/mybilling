<?php

namespace App\Jobs;

use RouterOS\Query;
use RouterOS\Client;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class IsolirPelangganJob implements ShouldQueue
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
         // Perintah update Comment di MikroTik
         $client = new Client(config('mikrotik.credential'));

         $query = new Query('/ppp/secret/print');
         $query->where('comment', 'BELUM-LUNAS');
         $secrets = $client->query($query)->read();
         
         foreach ($secrets as $secret) {
            $queryActive = (new Query('/ppp/active/print'))
                            ->where('name', $secret['name']);
            $client->query($queryActive)->read();

             $query = (new Query('/ppp/secret/set'))
                 ->equal('.id', $secret['.id'])
                 ->equal('profile', 'pelanggan-terisolir');
         
             $client->query($query)->read();
             
         }
    }
}
