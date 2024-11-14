<?php

namespace App\Http\Controllers;

use RouterOS\Query;
use RouterOS\Client;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
}
