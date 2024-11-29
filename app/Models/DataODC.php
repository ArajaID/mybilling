<?php

namespace App\Models;

use App\Models\DataODP;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataODC extends Model
{
    use HasFactory;

    protected $table = "tb_odc";
    protected $guarded = ['id'];

    public function odp()
    {
        return $this->hasMany(DataODP::class);
    }
}
