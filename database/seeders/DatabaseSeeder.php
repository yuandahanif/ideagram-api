<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Donation;
use App\Models\Idea;
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

        $images = [
            "https://live.staticflickr.com/4205/35047763926_6e8ca0e027_c.jpg",
            "https://live.staticflickr.com/1914/30477687977_a7e714e3fa.jpg",
            "https://live.staticflickr.com/5490/31029358451_375a214316_c.jpg",
            "https://live.staticflickr.com/65535/51716369167_66c9811ea3_c.jpg",
            "https://live.staticflickr.com/65535/51741827882_60ac4e5512_b.jpg",
        ];

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

        foreach ($categories as $c) {
            Category::create([
                'name' => $c
            ]);
        }

        Idea::create([
            'name' => 'ideagram',
            'description' => 'web untuk berbagi ide dan lain-lain.',
            'category_id' => 1,
            'due_date' => date("Y-m-d h:i:s", strtotime("+3 Months")),
            'donation_target' => 100000,
            'location_id' => 1,
            'user_id' => 1,
        ]);

        Idea::create([
            'name' => 'Owwl',
            'description' => 'penangkaran burung hantu.',
            'category_id' => 3,
            'due_date' => date("Y-m-d h:i:s", strtotime("+3 Months")),
            'donation_target' => 1000000,
            'location_id' => 2,
            'user_id' => 1,
        ]);

        $idea1 = Idea::find(1);

        // Image dan feedback
        foreach ($images as $i) {
            $idea1->feedbacks()->create([
                'name' => 'ini judul',
                'description' => 'ini deskripsi',
                'donation_min' => 10000
            ]);

            $idea1->images()->create([
                'name' => 'burung',
                'url' => $i
            ]);
        }

        // komentar
        for ($i = 0; $i < 10; $i++) {
            $idea1->comments()->create([
                'comment' => 'ini komen ke-' . $i,
                'user_id' => 1
            ]);
        }

        $idea1->refresh();

        // Donasi
        for ($i = 1; $i <= count($images); $i++) {
            for ($j = 0; $j < 2; $j++) {
                Donation::create([
                    'feedback_id' => $i,
                    'amount' => 1000,
                    'user_id' => 1
                ]);
            }
        }
    }
}
