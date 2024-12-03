<?php

use App\Jobs\CekBerlakuPromoJob;
use App\Jobs\GenerateTagihan;
use App\Jobs\IsolirPelangganJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new GenerateTagihan)
->monthlyOn(15, '00:01');

Schedule::job(new IsolirPelangganJob)
->monthlyOn(20, '23:59');

Schedule::job(new CekBerlakuPromoJob)->daily();