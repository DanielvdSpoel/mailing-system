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
            'subject' => fake()->word(),
            'text_body' => fake()->text(),
            'html_body' => fake()->word(),
            'message_uid' => fake()->randomNumber(),
            'message_id' => fake()->word(),
            'received_at' => Carbon::now(),
            'archived_at' => null,
            'marked_as_spam_at' => null,
            'read_at' => fake()->boolean() ? Carbon::now() : null,
            'is_draft' => false,
            'is_important' => fake()->boolean(),
            'needs_human_verification' => fake()->boolean(),
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
                'marked_as_spam_at' => Carbon::now(),
            ];
        });
    }

    public function spam(): Factory
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
