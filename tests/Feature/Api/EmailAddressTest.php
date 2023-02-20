<?php

use App\Models\EmailAddress;
use Illuminate\Testing\Fluent\AssertableJson;

it('can list email addresses', function () {
    EmailAddress::factory()->count(10)->create();

    $emailAddress = EmailAddress::orderBy('name')->first();

    $this->get(route('email-addresses.index'))
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 10)
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $emailAddress->id)
                ->where('email', $emailAddress->email)
                ->where('name', $emailAddress->name)
                ->etc()
            )
        );
});

it('can limit email addresses', function () {
    EmailAddress::factory()->count(10)->create();

    $this->get(route('email-addresses.index').'?limit=5')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 5));
});

it('can search for a email address by name', function () {
    EmailAddress::factory()->count(10)->create();
    EmailAddress::factory()->create(['name' => 'Test address']);

    $this->get(route('email-addresses.index').'?search=Test')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json->where('name', 'Test address')
                ->etc()
            )
        );
});

it('can search for a email address by email', function () {
    EmailAddress::factory()->count(10)->create();
    EmailAddress::factory()->create(['email' => 'test@email.com']);

    $this->get(route('email-addresses.index').'?search=Test')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json->where('email', 'test@email.com')
                ->etc()
            )
        );
});

it('can show a single email address', function () {
    $emailAddress = EmailAddress::factory()->create();

    $this->get(route('email-addresses.show', $emailAddress))
        ->assertJson([
            'data' => [
                'id' => $emailAddress->id,
                'name' => $emailAddress->name,
                'email' => $emailAddress->email,
            ],
        ]);
});
