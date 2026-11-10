<?php

use Wotz\FormArchitect\Filament\Resources\FormResource;
use Wotz\FormArchitect\Tests\Fixtures\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('has an index page', function () {
    $this->get(FormResource::getUrl('index'))->assertSuccessful();
});

it('has only an index and edit action', function () {
    expect(FormResource::getPages())
        ->toHaveCount(4)
        ->toHaveKeys(['index', 'create', 'edit', 'submissions']);
});
