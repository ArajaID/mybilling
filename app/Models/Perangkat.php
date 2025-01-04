<?php

namespace App\Models;

use App\Models\Pelanggan;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perangkat extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tb_perangkat";
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['nama_perangkat', 'merek', 'tipe', 'sn', 'catatan', 'is_active'])
        ->useLogName('Perangkat')
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnlyDirty(true);
    }

    // Relasi ke model pelanggan 1 perangkat 1 pelanggan
    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class, 'id_perangkat');
    }
}
