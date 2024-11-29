<?php

namespace App\Models;

use App\Models\DataODC;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataODP extends Model
{
    use HasFactory;

    protected $table = "tb_odp";
    protected $guarded = ['id'];

    public function odc()
    {
        return $this->belongsTo(DataODC::class);
    }
}
