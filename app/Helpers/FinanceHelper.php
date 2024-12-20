<?php
namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Tagihan;

class FinanceHelper {
    public static function calculateTagihan($startDate, $monthlyFee)
    {
        $startDate = Carbon::parse($startDate);
        $dueDate = Carbon::create($startDate->year, $startDate->month, 20);

        // Jika tanggal mulai lebih besar dari tanggal jatuh tempo, pindahkan ke bulan berikutnya
        if ($startDate->gt($dueDate)) {
            $dueDate->addMonth();
        }

        $currentDate = now();

        // Jika pelanggan masih dalam bulan pertama
        if ($startDate->format('Y-m') === $currentDate->format('Y-m')) {
            $daysInMonth = $startDate->daysInMonth;
            $daysUsed = $startDate->diffInDays($dueDate);

            // Hitung biaya prorata
            $proratedFee = ($daysUsed / $daysInMonth) * $monthlyFee;

            // Biaya prorata ditambah biaya bulanan penuh
            return round($proratedFee);
        }

        // Jika pelanggan sudah melewati bulan pertama, kenakan biaya bulanan penuh
        return round($monthlyFee, 2);
    }

    public static function tagihanExists($idPelanggan, $periodeBulan, $excludedDescription)
    {
        $query = Tagihan::where('id_pelanggan', $idPelanggan)
        ->where('periode_bulan', $periodeBulan);

        if ($excludedDescription) {
            $query->where('deskripsi', '!=', $excludedDescription);
        }

        return $query->exists();
    }
}