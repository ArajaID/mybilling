<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function create() {
        return view('pengeluaran.create');
    }
}
