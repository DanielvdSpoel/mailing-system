<?php

use App\Jobs\ResendSnoozedEmailNotifications;
use App\Models\Email;

it('removes the snooze status from emails', function () {
    $email = Email::factory(['snoozed_until' => now()->subDay()])->create();
    ResendSnoozedEmailNotifications::dispatchSync();
    expect($email->fresh()->snoozed_until)->toBeNull();
});

it('does not un snooze emails to early', function () {
    $email = Email::factory(['snoozed_until' => now()->addDay()])->create();
    ResendSnoozedEmailNotifications::dispatchSync();
    expect($email->fresh()->snoozed_until)->not->toBeNull();
});

