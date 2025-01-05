<?php

namespace App\Models;

use App\Models\Paket;
use App\Models\Promo;
use App\Models\DataODP;
use App\Models\Tagihan;
use App\Models\Perangkat;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "tb_pelanggan";
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['kode_pelanggan', 'nama_pelanggan', 'email', 'no_telepon', 'blok', 'rt', 'area', 'odp_id', 'id_perangkat', 'id_paket', 'user_pppoe', 'password_pppoe', 'is_active', 'aktivasi_layanan', 'tanggal_aktivasi'])
        ->useLogName('Pelanggan')
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
        ->logOnlyDirty(true);
    }

    public function scopeSearch($query, $val)
    {
        if(isset($val) ? $val : false) {
            return $query->where('kode_pelanggan', 'like', '%' . $val . '%')
                ->orWhere('nama_pelanggan', 'like', '%' . $val . '%')
                ->orWhere('blok', 'like', '%' . $val . '%');
        }
    }

    public function promo()
    {
        return $this->belongsToMany(Promo::class, 'tb_promopelanggan', 'id', 'id_promo')
        ->withPivot('tanggal_berakhir_promo', 'is_active');
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

    // relasi ke model perangkat 1 pelanggan memiliki 1 perangkat
    public function perangkat()
    {
        return $this->hasOne(Perangkat::class, 'id');
    }
}
