<?php

namespace App\Jobs;

use Carbon\Carbon;
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
            $tagihan->jumlah_tagihan    = $this->hitungJumlahTagihan($pelanggan);
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

    private function hitungJumlahTagihan($pelanggan)
    {
        $startDate = Carbon::parse($pelanggan->tanggal_aktivasi);

        // Tanggal jatuh tempo setiap tanggal 20
        $dueDate = Carbon::create($startDate->year, $startDate->month, 20);

        if ($startDate->gt($dueDate)) {
            $dueDate->addMonth();
        }

         // Total hari dalam bulan
        $daysInMonth = $startDate->daysInMonth;

        // Hitung jumlah hari yang terpakai
        $daysUsed = $startDate->diffInDays($dueDate);

        // Hitung biaya prorata
        $proratedFee = ($daysUsed / $daysInMonth) * $pelanggan->paket->harga;

        $totalTagihan = $pelanggan->paket->harga + round($proratedFee, 2);

        // Logika untuk menghitung jumlah tagihan
        // Misalnya, berdasarkan paket pelanggan
        return $totalTagihan;
    }
}
