<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 0; $i < 10; $i++){
            News::insert([
                'title' => Str::random(10),
                'content' => Str::random(400),
                'user_id' => rand(1, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } 
    }
}
