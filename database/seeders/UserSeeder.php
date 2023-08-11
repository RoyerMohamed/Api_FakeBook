<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'pseudo' => "Alan",
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('test'), // password
            'remember_token' => Str::random(10),
            'role_id' => 1,
        ]);

        User::create([
            'pseudo' => "user",
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('test'), // password
            'remember_token' => Str::random(10),
            'role_id' => 2,
        ]);
        User::factory(8)->create();
    }
}
