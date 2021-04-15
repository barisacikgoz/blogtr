<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = ['Hakkımızda', 'Vizyonumuz', 'Misyonumuz'];
        $count = 0;
        foreach ($pages as $page) {
            $count++;
            DB::table('pages')->insert([
                'title' => $page,
                'slug' => Str::slug($page),
                'image' => 'https://image.freepik.com/free-photo/side-view-cropped-unrecognizable-business-people-working-common-desk_1098-20474.jpg',
                'content' => 'lorem lorem ipsum dolor sit amet olermasdl alşsdlşaskd loremöa lasşidsa
                          lorem lorem ipsum dolor sit amet olermasdl alşsdlşaskd loremöa lasşidsa
                          lorem lorem ipsum dolor sit amet olermasdl alşsdlşaskd loremöa lasşidsa',
                'order' => $count,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
