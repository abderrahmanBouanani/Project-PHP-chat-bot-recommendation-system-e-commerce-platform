<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(['type' => 'admin'])->id,
        ];
    }
}