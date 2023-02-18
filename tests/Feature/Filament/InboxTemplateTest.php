<?php

use App\Filament\Resources\InboxTemplateResource\Pages\CreateInboxTemplate;
use App\Filament\Resources\InboxTemplateResource\Pages\EditInboxTemplate;
use App\Filament\Resources\InboxTemplateResource\Pages\ListInboxTemplates;
use App\Models\InboxTemplate;
use Filament\Pages\Actions\DeleteAction;
use function Pest\Faker\faker;
use function Pest\Livewire\livewire;

it('has inbox templates page', function () {
    InboxTemplate::factory()->count(10)->create();

    Livewire::test(ListInboxTemplates::class)->assertCanSeeTableRecords(
        InboxTemplate::limit(10)->get()
    );
});

it('can see the create button', function () {
    Livewire::test(ListInboxTemplates::class)->assertPageActionExists('create');
});

it('can create a inbox template', function () {
    $newData = InboxTemplate::factory()->make();

    livewire(CreateInboxTemplate::class)
        ->fillForm([
            'name' => $newData->name,
            'imap_host' => $newData->imap_host,
            'imap_port' => $newData->imap_port,
            'imap_encryption' => $newData->imap_encryption,
            'smtp_host' => $newData->smtp_host,
            'smtp_port' => $newData->smtp_port,
            'smtp_encryption' => $newData->smtp_encryption,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(InboxTemplate::class, [
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
    $newData = InboxTemplate::factory()->make();

    livewire(CreateInboxTemplate::class)
        ->fillForm([
            'name' => null,
            'imap_host' => $newData->imap_host,
            'imap_port' => faker()->word(), //todo fix this
            'imap_encryption' => $newData->imap_encryption,
            'smtp_host' => $newData->smtp_host,
            'smtp_port' => $newData->smtp_port,
            'smtp_encryption' => $newData->smtp_encryption,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
//            'imap_port' => 'numeric|min:1',
        ]);
});

it('can see the edit button', function () {
    Livewire::test(ListInboxTemplates::class)->assertTableActionExists('edit');
});

it('can retrieve data', function () {
    $inboxTemplate = InboxTemplate::factory()->create();

    livewire(EditInboxTemplate::class, [
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
    Livewire::test(EditInboxTemplate::class, [
        'record' => InboxTemplate::factory()->create()->id,
    ])->assertPageActionExists('delete');
});

it('can delete', function () {
    $inboxTemplate = InboxTemplate::factory()->create();

    livewire(EditInboxTemplate::class, [
        'record' => $inboxTemplate->getKey(),
    ])->callPageAction(DeleteAction::class);

    $this->assertDatabaseMissing(InboxTemplate::class, [
        'id' => $inboxTemplate->id,
        'deleted_at' => null,
    ]);
});
