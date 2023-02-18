<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmailAddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'label' => $this->faker->word(),
            'mailbox' => $this->faker->word(),
            'domain' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'verified_at' => $this->faker->boolean() ? Carbon::now() : null,
        ];
    }
}
