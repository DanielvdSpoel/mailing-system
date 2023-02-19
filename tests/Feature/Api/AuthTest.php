<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

it('cannot acces profile if not logged in', function () {
    Auth::logout();
    $this->withHeader('Accept', 'application/json')
        ->get(route('user'))->assertStatus(401);
});

it('cannot acces emails if not logged in', function () {
    Auth::logout();
    $this->withHeader('Accept', 'application/json')
        ->get(route('emails.index'))->assertStatus(401);
});

it('can login', function () {
    Auth::logout();
    $user = User::factory()->create();
    $this->withHeader('Accept', 'application/json')
        ->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertJson(fn (AssertableJson $json) => $json->has('token'));
});
