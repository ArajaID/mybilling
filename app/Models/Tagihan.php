<?php

namespace App\Models;

use App\Models\Pelanggan;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Tagihan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tb_tagihan";
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['kode_tagihan', 'id_pelanggan', 'tanggal_tagihan', 'periode_bulan', 'jumlah_tagihan', 'status_pembayaran', 'deskripsi'])
        ->useLogName('Tagihan')
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnlyDirty(true);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
}
