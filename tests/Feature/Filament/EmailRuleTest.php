<?php

use App\Filament\Resources\EmailRuleResource\Pages\CreateEmailRule;
use App\Filament\Resources\EmailRuleResource\Pages\EditEmailRule;
use App\Filament\Resources\EmailRuleResource\Pages\ListEmailRules;
use App\Models\EmailRule;
use Filament\Pages\Actions\DeleteAction;
use function Pest\Livewire\livewire;

it('has email rule page', function () {
    EmailRule::factory()->count(10)->create();

    Livewire::test(ListEmailRules::class)->assertCanSeeTableRecords(
        EmailRule::limit(10)->get()
    );
});

it('can see the create button', function () {
    Livewire::test(ListEmailRules::class)->assertPageActionExists('create');
});

it('can create a email rule', function () {
    $newData = EmailRule::factory()->make();

    livewire(CreateEmailRule::class)
        ->fillForm([
            'name' => $newData->name,
            'conditions' => $newData->conditions,
            'actions' => $newData->actions,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(EmailRule::class, [
        'name' => $newData->name,
        'conditions' => json_encode($newData->conditions),
        'actions' => json_encode($newData->actions),
    ]);
});

it('can see the edit button', function () {
    Livewire::test(ListEmailRules::class)->assertTableActionExists('edit');
});

it('can retrieve data', function () {
    $emailRule = EmailRule::factory()->create();

    livewire(EditEmailRule::class, [
        'record' => $emailRule->getKey(),
    ])
        ->assertFormSet([
            'name' => $emailRule->name,
        ]);
});

it('can see the delete button', function () {
    Livewire::test(EditEmailRule::class, [
        'record' => EmailRule::factory()->create()->id,
    ])->assertPageActionExists('delete');
});

it('can delete', function () {
    $emailRule = EmailRule::factory()->create();

    livewire(EditEmailRule::class, [
        'record' => $emailRule->getKey(),
    ])->callPageAction(DeleteAction::class);

    $this->assertDatabaseMissing(EmailRule::class, [
        'id' => $emailRule->id,
    ]);
});
