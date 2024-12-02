<?php

namespace App\Models;

use App\Models\Pelanggan;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paket extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tb_paket";
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['nama_paket', 'harga', 'bandwidth', 'is_active'])
        ->useLogName('Paket')
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnlyDirty(true);
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
