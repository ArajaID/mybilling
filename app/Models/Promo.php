<?php

namespace App\Models;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promo extends Model
{
    use HasFactory;

    protected $table = "tb_promo";
    protected $guarded = ['id'];

    public function pelanggan()
    {
        return $this->belongsToMany(Pelanggan::class);
    }

}
