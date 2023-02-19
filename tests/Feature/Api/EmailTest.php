<?php

use App\Models\Email;
use Illuminate\Testing\Fluent\AssertableJson;

it('can list emails', function () {
    Email::factory()->count(10)->create();

    $email = Email::orderBy('received_at')->first();

    //todo: check if labels are returned
    $this->get(route('emails.index'))->assertJson(fn (AssertableJson $json) => $json->has('data', 10)->has('meta')->has('links')
        ->has('data.0', fn (AssertableJson $json) => $json->where('id', $email->id)
            ->where('subject', $email->subject)
            ->where('text_body', $email->text_body)
            ->where('html_body', $email->html_body)
            ->where('received_at', $email->received_at)
            ->where('is_read', (bool) $email->read_at)
            ->etc()
        )
    );
});

it('can filter on inbox', function () {
    $email = Email::factory()->create();

    $this->get(route('emails.index').'?inbox_id='.$email->inbox->id)
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $email->id)->etc())
        );
});

it('can filter on label', function () {
    $email = Email::factory()->hasLabels(1)->create();
    $label = $email->labels->first();

    $this->get(route('emails.index').'?label_id='.$label->id)
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $email->id)->etc())
        );
});

it('can filter on sender', function () {
    $email = Email::factory()->create();

    $this->get(route('emails.index').'?sender_id='.$email->senderAddress->id)
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $email->id)->etc())
        );
});

it('can search emails', function () {
    $email = Email::factory()->create([
        'subject' => 'subject55',
        'text_body' => 'text_body55',
        'html_body' => 'html_body55',
    ]);

    $this->get(route('emails.index').'?search=subject55')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $email->id)->etc())
        );

    $this->get(route('emails.index').'?search=text_body55')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $email->id)->etc())
        );

    $this->get(route('emails.index').'?search=html_body55')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $email->id)->etc())
        );

    $this->get(route('emails.index').'?search='.$email->senderAddress->email)
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 1)->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('id', $email->id)->etc())
        );
});

it('can filter out archived emails', function () {
    Email::factory()->archived()->count(10)->create();
    Email::factory()->count(10)->create();

    $this->get(route('emails.index').'?per_page=100')
        ->assertJson(fn (AssertableJson $json) => $json->has('data')->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('is_archived', false)->etc())
        );
});

it('can filter out draft emails', function () {
    Email::factory()->draft()->count(10)->create();
    Email::factory()->count(10)->create();

    $this->get(route('emails.index').'?per_page=100')
        ->assertJson(fn (AssertableJson $json) => $json->has('data')->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('is_draft', false)->etc())
        );
});

it('can filter out trashed emails', function () {
    Email::factory()->trashed()->count(10)->create();
    Email::factory()->count(10)->create();

    $this->get(route('emails.index').'?per_page=100')
        ->assertJson(fn (AssertableJson $json) => $json->has('data')->etc()
            ->has('data.0', fn (AssertableJson $json) => $json->where('is_deleted', false)->etc())
        );
});

it('can limit emails', function () {
    Email::factory()->count(10)->create();

    $this->get(route('emails.index').'?limit=5&no_pagination=true')
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 5));
});

it('can disable email pagination', function () {
    Email::factory()->count(10)->create();

    $this->get(route('emails.index').'?&no_pagination=true')
        ->assertJson(fn (AssertableJson $json) => $json->has('data')->missing('links')->missing('meta'));
});
