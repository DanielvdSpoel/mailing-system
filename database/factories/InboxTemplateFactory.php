<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InboxTemplateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'imap_host' => fake()->domainName(),
            'imap_port' => fake()->numberBetween(1, 100),
            'imap_encryption' => fake()->word(),
            'smtp_host' => fake()->domainName(),
            'smtp_port' => fake()->numberBetween(1, 100),
            'smtp_encryption' => fake()->word(),
        ];
    }
}
