<?php

namespace App\Models;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paket extends Model
{
    use HasFactory;

    protected $table = "tb_paket";
    protected $guarded = ['id'];

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
