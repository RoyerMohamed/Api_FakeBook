<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; 
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
            'user_id' => rand( 1 , User::count()),
            'post_id' => rand( 1 , Post::count()),
            'image' => 'default_picture_' . rand( 1 , 5) . '.jpg',
            'tags' => fake()->word(3,true),
        ];
    }
}
