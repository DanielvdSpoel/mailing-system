<?php

use App\Filament\Resources\LabelResource\Pages\CreateLabel;
use App\Filament\Resources\LabelResource\Pages\EditLabel;
use App\Filament\Resources\LabelResource\Pages\ListLabels;
use App\Models\Label;
use Filament\Pages\Actions\DeleteAction;
use function Pest\Livewire\livewire;

it('has labels page', function () {
    Label::factory()->count(10)->create();
    Livewire::test(ListLabels::class)->assertCanSeeTableRecords(
        Label::limit(10)->get()
    );
});

it('can see the create button', function () {
    Livewire::test(ListLabels::class)->assertPageActionExists('create');
});

it('can create a label', function () {
    $newData = Label::factory()->make();

    livewire(CreateLabel::class)
        ->fillForm([
            'name' => $newData->name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Label::class, [
        'name' => $newData->name,
    ]);
});

it('can validate input', function () {
    livewire(CreateLabel::class)
        ->fillForm([
            'name' => null,
            'color' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'color' => 'required']);
});

it('can see the edit button', function () {
    Livewire::test(ListLabels::class)->assertTableActionExists('edit');
});

it('can retrieve data', function () {
    $label = Label::factory()->create();

    livewire(EditLabel::class, [
        'record' => $label->getKey(),
    ])
        ->assertFormSet([
            'name' => $label->name,
            'color' => $label->color,
        ]);
});

it('can see the delete button', function () {
    Livewire::test(EditLabel::class, [
        'record' => Label::factory()->create()->id,
    ])->assertPageActionExists('delete');
});

it('can delete', function () {
    $label = Label::factory()->create();

    livewire(EditLabel::class, [
        'record' => $label->getKey(),
    ])->callPageAction(DeleteAction::class);

    $this->assertDatabaseMissing(Label::class, [
        'id' => $label->id,
        'deleted_at' => null,
    ]);
});
