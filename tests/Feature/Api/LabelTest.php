<?php

use App\Models\Label;
use Illuminate\Testing\Fluent\AssertableJson;

it('can list labels', function () {
    Label::factory()->count(10)->create();

    $label = Label::orderBy('name')->first();

    $this->get(route('labels.index'))
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 10)
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $label->id)
                ->where('name', $label->name)
                ->where('color', $label->color)
                ->etc()
            )
        );
});

it('can limit labels', function () {
    Label::factory()->count(10)->create();

    $this->get(route('labels.index').'?limit=5')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 5));
});

it('can search for a label', function () {
    Label::factory()->count(10)->create();
    Label::factory()->create(['name' => 'Test Label']);

    $this->get(route('labels.index').'?search=Test')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) => $json->where('name', 'Test Label')
                ->etc()
            )
        );
});

it('can show a single label', function () {
    $label = Label::factory()->create();

    $this->get(route('labels.show', $label))
        ->assertJson([
            'data' => [
                'id' => $label->id,
                'name' => $label->name,
            ],
        ]);
});
