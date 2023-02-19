<?php

namespace Database\Factories;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailAttachmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'filename' => fake()->word(),
            'inbox_id' => Email::factory(),
        ];
    }
}
