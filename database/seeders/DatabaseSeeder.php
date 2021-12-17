<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $locations = ['Bulan', 'Yogyakarta', 'Hatimu 💕'];
        $categories = ['Teknologi', 'Makanan', 'Lingkungan'];

        User::create([
            'name' => 'yuanda',
            'email' => 'yuan@da.me',
            'password' => bcrypt('12345678')
        ]);

        foreach ($locations as $l) {
            Location::create([
                'name' => $l
            ]);
        }
    }
}
