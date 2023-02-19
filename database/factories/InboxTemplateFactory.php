<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InboxTemplateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'imap_host' => $this->faker->domainName(),
            'imap_port' => $this->faker->numberBetween(1, 100),
            'imap_encryption' => $this->faker->word(),
            'smtp_host' => $this->faker->domainName(),
            'smtp_port' => $this->faker->numberBetween(1, 100),
            'smtp_encryption' => $this->faker->word(),
        ];
    }
}
