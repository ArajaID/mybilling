<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Paket;
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
            'name' => 'Jamil',
            'email' => 'sa@demo.com',
            'password' => bcrypt('password'),
        ]);

        Paket::create([
            'nama_paket' => 'Paket Upto 5',
            'harga' => 110000,
            'bandwidth' => "bw5",
        ]);

        Paket::create([
            'nama_paket' => 'Paket Upto 10',
            'harga' => 180000,
            'bandwidth' => "bw10",
        ]);

        Paket::create([
            'nama_paket' => 'Paket Upto 20',
            'harga' => 230000,
            'bandwidth' => "bw20",
        ]);
    }
}
