<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromoPelanggan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tb_promopelanggan";
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['berlaku_bulan', 'id_pelanggan', 'id_promo', 'tanggal_klaim', 'tanggal_berakhir_promo'])
        ->useLogName('Promo Pelanggan')
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnlyDirty(true);
    }
}
