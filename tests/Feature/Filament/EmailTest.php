<?php

use App\Filament\Resources\EmailResource\Pages\ListEmails;
use App\Filament\Resources\EmailResource\Pages\ViewEmail;
use App\Models\Email;
use function Pest\Livewire\livewire;

it('Can list emails', function () {
    Email::factory()->count(10)->create();

    Livewire::test(ListEmails::class)->assertCanSeeTableRecords(
        Email::limit(10)->get()
    );
});

it('can see the view button', function () {
    Livewire::test(ListEmails::class)->assertTableActionExists('view');
});

it('can retrieve data', function () {
    $email = Email::factory()->create();

    livewire(ViewEmail::class, [
        'record' => $email->getKey(),
    ])
        ->assertFormSet([
            'subject' => $email->subject,
        ]);
});
