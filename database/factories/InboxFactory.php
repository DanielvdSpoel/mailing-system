<?php

namespace Database\Factories;

use App\Models\InboxTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InboxFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'imap_host' => fake()->domainName(),
            'imap_port' => fake()->randomNumber(),
            'imap_encryption' => fake()->word(),
            'imap_username' => fake()->userName(),
            'imap_password' => fake()->password(),
            'same_credentials' => fake()->boolean(),
            'smtp_host' => fake()->domainName(),
            'smtp_port' => fake()->randomNumber(),
            'smtp_encryption' => fake()->word(),
            'smtp_username' => fake()->userName(),
            'smtp_password' => fake()->password(),
            'last_successful_connection_at' => Carbon::now(),
            'folder_to_flags_mapping' => null,

            'template_id' => InboxTemplate::factory(),
        ];
    }
}
