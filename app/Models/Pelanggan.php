<?php

namespace App\Models;

use App\Models\Paket;
use App\Models\Promo;
use App\Models\Tagihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = "tb_pelanggan";
    protected $guarded = ['id'];

    public function promo()
    {
        return $this->belongsToMany(Promo::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }
}
