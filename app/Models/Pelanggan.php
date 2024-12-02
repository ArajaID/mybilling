<?php

namespace App\Models;

use App\Models\Paket;
use App\Models\Promo;
use App\Models\DataODP;
use App\Models\Tagihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pelanggan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tb_pelanggan";
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['kode_pelanggan', 'nama_pelanggan', 'email', 'no_telepon', 'blok', 'rt', 'area', 'odp_id', 'id_paket', 'user_pppoe', 'password_pppoe', 'is_active', 'aktivasi_layanan', 'tanggal_aktivasi'])
        ->useLogName('Pelanggan')
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnlyDirty(true);
    }

    public function promo()
    {
        return $this->belongsToMany(Promo::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }

    public function odpData()
    {
        return $this->belongsTo(DataODP::class, 'odp_id');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }
}
