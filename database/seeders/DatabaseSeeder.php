<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $images = [
            'depot_img_1.jpg',
            'depot_img_2.jpg',
            'depot_img_3.jpg',
        ];

        $item = 0;

        foreach($images as $image){
            DB::table('tb_artikel')->insert([
                'judul' => 'Judul '.$item + 1,
                'deskripsi' => fake()-> paragraph(2),
                'image' => $image,
            ]);

            $item += 1;
        }

        User::factory()->create([
            'name' => 'admin',
            'email' => 'test@example.com',
            'password' => Hash::make('123'),
        ]);
    }
}
