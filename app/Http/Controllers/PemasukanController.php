<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function create() {
        return view('pemasukan.create');
    }
}
