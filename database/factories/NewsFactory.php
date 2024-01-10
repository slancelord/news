<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\News;
use App\Models\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => Str::random(10),
            'content' => Str::random(400),
            'user_id' => User::inRandomOrder()->first(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure() 
    {
        return $this->afterCreating(function (News $news) {
            
                $news->tags()->attach(Tag::pluck('id')->random(rand(1, Tag::count())));
        });
    }
}
