<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

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
        $user_id = User::select('id')->get()->toArray();

        return [
            'title' => Str::random(10),
            'content' => Str::random(400),
            'user_id' => array_rand($user_id),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
