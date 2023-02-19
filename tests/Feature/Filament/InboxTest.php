<?php

use App\Filament\Resources\InboxResource\Pages\CreateInbox;
use App\Filament\Resources\InboxResource\Pages\EditInbox;
use App\Filament\Resources\InboxResource\Pages\ListInboxes;
use App\Filament\Resources\InboxResource\Pages\ViewInbox;
use App\Models\Inbox;
use Filament\Pages\Actions\DeleteAction;
use function Pest\Livewire\livewire;

it('has inboxes page', function () {
    Inbox::factory()->count(10)->create();

    Livewire::test(ListInboxes::class)->assertCanSeeTableRecords(
        Inbox::limit(10)->get()
    );
});

it('can see the create button', function () {
    Livewire::test(ListInboxes::class)->assertPageActionExists('create');
});

it('can create a inbox', function () {
    $newData = Inbox::factory()->make();

    livewire(CreateInbox::class)
        ->fillForm([
            'name' => $newData->name,
            'imap_host' => $newData->imap_host,
            'imap_port' => $newData->imap_port,
            'imap_encryption' => $newData->imap_encryption,
            'imap_username' => $newData->imap_username,
            'imap_password' => $newData->imap_password,
            'smtp_host' => $newData->smtp_host,
            'smtp_port' => $newData->smtp_port,
            'smtp_encryption' => $newData->smtp_encryption,
            'smtp_username' => $newData->smtp_username,
            'smtp_password' => $newData->smtp_password,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Inbox::class, [
        'name' => $newData->name,
        'imap_host' => $newData->imap_host,
        'imap_port' => $newData->imap_port,
        'imap_encryption' => $newData->imap_encryption,
        'smtp_host' => $newData->smtp_host,
        'smtp_port' => $newData->smtp_port,
        'smtp_encryption' => $newData->smtp_encryption,
    ]);
});

it('can validate input', function () {
    $newData = Inbox::factory()->make();

    livewire(CreateInbox::class)
        ->fillForm([
            'name' => null,
            'imap_host' => $newData->imap_host,
            'imap_port' => $newData->imap_port,
            'imap_encryption' => $newData->imap_encryption,
            'imap_username' => $newData->imap_username,
            'imap_password' => $newData->imap_password,
            'smtp_host' => $newData->smtp_host,
            'smtp_port' => $newData->smtp_port,
            'smtp_encryption' => $newData->smtp_encryption,
            'smtp_username' => $newData->smtp_username,
            'smtp_password' => $newData->smtp_password,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
        ]);
});

it('can see the edit button', function () {
    Livewire::test(ListInboxes::class)->assertTableActionExists('edit');
});

it('can retrieve data', function () {
    $inboxTemplate = Inbox::factory()->create();

    livewire(ViewInbox::class, [
        'record' => $inboxTemplate->getKey(),
    ])
        ->assertFormSet([
            'name' => $inboxTemplate->name,
            'imap_host' => $inboxTemplate->imap_host,
            'imap_port' => $inboxTemplate->imap_port,
            'imap_encryption' => $inboxTemplate->imap_encryption,
            'smtp_host' => $inboxTemplate->smtp_host,
            'smtp_port' => $inboxTemplate->smtp_port,
            'smtp_encryption' => $inboxTemplate->smtp_encryption,
        ]);
});

it('can see the delete button', function () {
    Livewire::test(EditInbox::class, [
        'record' => Inbox::factory()->create()->id,
    ])->assertPageActionExists('delete');
});

it('can delete', function () {
    $inboxTemplate = Inbox::factory()->create();

    livewire(EditInbox::class, [
        'record' => $inboxTemplate->getKey(),
    ])->callPageAction(DeleteAction::class);

    $this->assertDatabaseMissing(Inbox::class, [
        'id' => $inboxTemplate->id,
        'deleted_at' => null,
    ]);
});
