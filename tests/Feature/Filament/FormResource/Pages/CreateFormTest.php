<?php

use Codedor\FormArchitect\Filament\Resources\Pages\CreateForm;
use Codedor\FormArchitect\Models\Form;
use Codedor\FormArchitect\Tests\Fixtures\Models\User;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can create a form', function () {
    $newData = Form::factory()->make();

    livewire(CreateForm::class)
        ->fillForm([
            'name' => $newData->name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Form::class, [
        'name' => $newData->name,
    ]);
});
