<?php

namespace App\Models;

use App\Models\Pelanggan;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promo extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tb_promo";
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['kode_promo', 'nama_promo', 'deskripsi', 'diskon', 'tambahan_speed', 'tanggal_mulai', 'tanggal_berakhir', 'syarat_ketentuan', 'status_promo'])
        ->useLogName('Promo')
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnlyDirty(true);
    }

    public function pelanggan()
    {
        return $this->belongsToMany(Pelanggan::class);
    }

}
