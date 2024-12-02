<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaksi extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tb_transaksi";
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['jenis_transaksi', 'tanggal', 'sumber', 'debit', 'kredit', 'kategori', 'metode_pembayaran', 'deskripsi', 'lampiran'])
        ->useLogName('Transaksi')
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnlyDirty(true);
    }
}
