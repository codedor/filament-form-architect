<?php

use Illuminate\Support\Str;
use Wotz\FormArchitect\Livewire\RenderedForm;
use Wotz\FormArchitect\Models\Form;
use Wotz\FormArchitect\Models\FormSubmission;

use function Pest\Livewire\livewire;

function formWithSpacer(array $spacerData): Form
{
    return Form::factory()->create([
        'fields' => [
            [
                (string) Str::uuid() => [
                    'type' => 'TextInputBlock',
                    'width' => 12,
                    'data' => [
                        'is_required' => false,
                        'en' => ['online' => true, 'label' => 'Name', 'gdpr_notice' => null],
                        'working_title' => 'Name',
                    ],
                ],
                (string) Str::uuid() => [
                    'type' => 'SpacerBlock',
                    'width' => 12,
                    'data' => $spacerData,
                ],
            ],
        ],
    ]);
}

it('renders a spacer at its height', function () {
    livewire(RenderedForm::class, ['form' => formWithSpacer(['height' => 40, 'en' => ['online' => true]])])
        ->assertSeeHtml('style="height: 40px"');
});

it('uses the default height without one', function () {
    livewire(RenderedForm::class, ['form' => formWithSpacer(['en' => ['online' => true]])])
        ->assertSeeHtml('style="height: 25px"');
});

it('leaves the spacer out in a language it is offline in', function () {
    livewire(RenderedForm::class, ['form' => formWithSpacer(['height' => 40, 'en' => ['online' => false]])])
        ->assertDontSeeHtml('style="height: 40px"');
});

it('does not store the spacer in a submission', function () {
    $form = formWithSpacer(['height' => 40, 'en' => ['online' => true]]);
    $nameKey = array_keys($form->fields[0])[0];

    livewire(RenderedForm::class, ['form' => $form])
        ->set("fields.{$nameKey}", 'John Doe')
        ->call('saveData')
        ->assertHasNoErrors();

    expect(collect(FormSubmission::first()->data)->pluck('key')->all())->toBe([$nameKey]);
});
