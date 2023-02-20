<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmailAddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'mailbox' => fake()->word(),
            'domain' => fake()->word(),
            'email' => fake()->unique()->safeEmail(),
            'verified_at' => fake()->boolean() ? Carbon::now() : null,
        ];
    }
}
