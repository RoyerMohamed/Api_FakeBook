<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph(),
            'User_id' => rand( 1 , User::count()),
            'image' => 'default_picture_' .rand( 1 , 5) . '.jpg',
            'tags' => fake()->word(3,true),
        ];
    }
}
