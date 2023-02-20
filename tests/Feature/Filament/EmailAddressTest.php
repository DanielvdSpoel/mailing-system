<?php

use App\Filament\Resources\EmailAddressResource\Pages\CreateEmailAddress;
use App\Filament\Resources\EmailAddressResource\Pages\EditEmailAddress;
use App\Filament\Resources\EmailAddressResource\Pages\ListEmailAddresses;
use App\Filament\Resources\EmailAddressResource\Pages\ViewEmailAddress;
use App\Models\EmailAddress;
use Filament\Pages\Actions\DeleteAction;
use function Pest\Livewire\livewire;

it('has email addresses page', function () {
    EmailAddress::factory()->count(10)->create();

    Livewire::test(ListEmailAddresses::class)->assertCanSeeTableRecords(
        EmailAddress::limit(10)->get()
    );
});

it('can see the create button', function () {
    Livewire::test(ListEmailAddresses::class)->assertPageActionExists('create');
});

it('can create a email address', function () {
    $newData = EmailAddress::factory()->make();

    livewire(CreateEmailAddress::class)
        ->fillForm([
            'name' => $newData->name,
            'mailbox' => $newData->mailbox,
            'domain' => $newData->domain,
            'email' => $newData->email,
            'verified_at' => $newData->verified_at,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(EmailAddress::class, [
        'name' => $newData->name,
        'mailbox' => $newData->mailbox,
        'domain' => $newData->domain,
        'email' => $newData->email,
    ]);
});

it('can validate input', function () {
    $newData = EmailAddress::factory()->make();

    livewire(CreateEmailAddress::class)
        ->fillForm([
            'name' => null,
            'mailbox' => $newData->mailbox,
            'domain' => $newData->domain,
            'email' => fake()->word(),
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'email' => 'email']);
});

it('can see the edit button', function () {
    Livewire::test(ListEmailAddresses::class)->assertTableActionExists('edit');
});

it('can retrieve data', function () {
    $emailAddress = EmailAddress::factory()->create();

    livewire(ViewEmailAddress::class, [
        'record' => $emailAddress->getKey(),
    ])
        ->assertFormSet([
            'name' => $emailAddress->name,
            'email' => $emailAddress->email,
        ]);
});

it('can see the delete button', function () {
    Livewire::test(EditEmailAddress::class, [
        'record' => EmailAddress::factory()->create()->id,
    ])->assertPageActionExists('delete');
});

it('can delete', function () {
    $emailAddress = EmailAddress::factory()->create();

    livewire(EditEmailAddress::class, [
        'record' => $emailAddress->getKey(),
    ])->callPageAction(DeleteAction::class);

    $this->assertDatabaseMissing(EmailAddress::class, [
        'id' => $emailAddress->id,
        'deleted_at' => null,
    ]);
});
