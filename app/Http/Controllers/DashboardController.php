<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use App\Models\Paket;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $client = new Client(config('mikrotik.credential'));

        $query = new Query('/ppp/secret/print');
        $query->where('comment', 'BELUM-LUNAS');
        $secrets = $client->query($query)->read();
        
        foreach ($secrets as $secret) {
           $queryActive = (new Query('/ppp/active/print'))
                           ->where('name', $secret['name']);
            $response = $client->query($queryActive)->read();
   dd($response);
            $queryRemoveActive = (new Query('/ppp/active/remove'))->where('.id', $response[0]['.id']);
            dd($client->query($queryRemoveActive)->read());

            $query = (new Query('/ppp/secret/set'))
                ->equal('.id', $secret['.id'])
                ->equal('profile', 'pelanggan-terisolir');
        
            $client->query($query)->read();
            
        }

        $queryPPPActive = (new Query('/ppp/active/print'));
    
        $pppActive = $client->query($queryPPPActive)->read();

        $totalPelanggan = Pelanggan::where('is_active', 1)->count();
        $tagihanActive = Tagihan::where('status_pembayaran', 'BELUM-LUNAS')->count();
        $paketActive = Paket::where('is_active', 1)->count();

        return view('dashboard', [
            'pppActive'         => count($pppActive),
            'totalPelanggan'    => $totalPelanggan,
            'tagihanActive'     => $tagihanActive,
            'paketActive'       => $paketActive,
        ]);
    }
}
