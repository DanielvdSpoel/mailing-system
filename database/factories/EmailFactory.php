<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\EmailAddress;
use App\Models\Inbox;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subject' => $this->faker->word(),
            'text_body' => $this->faker->text(),
            'html_body' => $this->faker->word(),
            'message_uid' => $this->faker->randomNumber(),
            'message_id' => $this->faker->word(),
            'received_at' => Carbon::now(),
            'archived_at' => null,
            'read_at' => $this->faker->boolean() ? Carbon::now() : null,
            'is_draft' => false,
            'needs_human_verification' => $this->faker->boolean(),
            'auto_filtered_at' => Carbon::now(),

            'reply_to_address_id' => EmailAddress::factory(),
            'sender_address_id' => EmailAddress::factory(),
            'conversation_id' => Conversation::factory(),
            'inbox_id' => Inbox::factory(),
        ];


    }

    public function archived(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'archived_at' => Carbon::now(),
            ];
        });
    }

    public function draft(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_draft' => true,
            ];
        });
    }


}
