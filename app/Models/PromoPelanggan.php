<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromoPelanggan extends Model
{
    use HasFactory;

    protected $table = "tb_promopelanggan";
    protected $guarded = ['id'];
}
