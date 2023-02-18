<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InboxTemplateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'imap_host' => $this->faker->domainName(),
            'imap_port' => $this->faker->word(),
            'imap_encryption' => $this->faker->word(),
            'smtp_host' => $this->faker->domainName(),
            'smtp_port' => $this->faker->word(),
            'smtp_encryption' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
