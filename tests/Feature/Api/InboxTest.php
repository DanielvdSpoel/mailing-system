<?php

use App\Models\Inbox;
use Illuminate\Testing\Fluent\AssertableJson;

it('can list inboxes', function () {
    Inbox::factory()->count(10)->create();

    $inbox = Inbox::orderBy('name')->first();

    $this->get(route('inboxes.index'))
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 10)
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $inbox->id)
                ->where('name', $inbox->name)
                ->where('senderAddresses', $inbox->senderAddresses->pluck('email'))
                ->missing('imap_password')
                ->missing('smtp_password')
                ->etc()
            )
        );
});

it('can search for a inbox by name', function () {
    Inbox::factory()->count(10)->create();
    Inbox::factory()->create(['name' => 'Test Inbox']);

    $this->get(route('inboxes.index').'?search=Test')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json->where('name', 'Test Inbox')
                ->etc()
            )
        );
});

it('can search for a inbox by sender email', function () {
    Inbox::factory()->count(10)->create();
    Inbox::factory()
        ->hasSenderAddresses(1, ['email' => 'test@email.com'])
        ->create(['name' => 'Test Inbox']);

    $this->get(route('inboxes.index').'?search=test@email.com')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json->where('name', 'Test Inbox')
                ->etc()
            )
        );
});

it('can show a single inbox', function () {
    $inbox = Inbox::factory()->create();

    $this->get(route('inboxes.show', $inbox))
        ->assertJson(fn (AssertableJson $json) => $json->has('data', fn (AssertableJson $json) => $json->where('id', $inbox->id)
                ->where('name', $inbox->name)
                ->where('senderAddresses', $inbox->senderAddresses->pluck('email'))
                ->missing('imap_password')
                ->missing('smtp_password')
                ->etc()
            )
        );
});
