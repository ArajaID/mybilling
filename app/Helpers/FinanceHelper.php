<?php
namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Tagihan;

class FinanceHelper {
    public static function calculateTagihan($startDate, $monthlyFee)
    {
        $startDate = Carbon::parse($startDate);
        $dueDate = Carbon::create($startDate->year, $startDate->month, 20);

        if ($startDate->gt($dueDate)) {
            $dueDate->addMonth();
        }

        $currentDate = now();

        if ($startDate->format('Y-m') === $currentDate->format('Y-m')) {
            $daysInMonth = $startDate->daysInMonth;
            $daysUsed = $startDate->diffInDays($dueDate);

            $proratedFee = ($daysUsed / $daysInMonth) * $monthlyFee;

            return round($proratedFee + $monthlyFee, 2);
        }

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