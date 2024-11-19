<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index() {
        $dataTagihan = Tagihan::with('pelanggan')->get();
        
        return view('tagihan.index', [
            'tagihan' => $dataTagihan
        ]);
    }
}
