<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Paket;
use App\Models\Promo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Abdul Rahman Jamil',
            'email' => 'jamil@aionios.net',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Haikal',
            'email' => 'haikal@aionios.net',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Fachrie',
            'email' => 'fachrie@aionios.net',
            'password' => bcrypt('password'),
        ]);

        Promo::create([
            'kode_promo' => 0,
            'nama_promo' => 'Tanpa Promo',
            'tanggal_berakhir' => '2999/01/01'
        ]);
    }
}
