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
            'label' => $this->faker->word(),
            'imap_host' => $this->faker->domainName(),
            'imap_port' => $this->faker->randomNumber(),
            'imap_encryption' => $this->faker->word(),
            'imap_username' => $this->faker->userName(),
            'imap_password' => bcrypt($this->faker->password()),
            'same_credentials' => $this->faker->boolean(),
            'smtp_host' => $this->faker->domainName(),
            'smtp_port' => $this->faker->randomNumber(),
            'smtp_encryption' => $this->faker->word(),
            'smtp_username' => $this->faker->userName(),
            'smtp_password' => bcrypt($this->faker->password()),
            'last_successful_connection_at' => Carbon::now(),
            'folder_to_flags_mapping' => "{}",

            'template_id' => InboxTemplate::factory(),
        ];
    }
}
