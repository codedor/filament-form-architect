<?php

use Codedor\FormArchitect\Filament\Resources\FormResource;
use Codedor\FormArchitect\Livewire\RenderedForm;
use Codedor\FormArchitect\Mail\SendFormSubmission;
use Codedor\FormArchitect\Models\Form;
use Codedor\FormArchitect\Models\FormSubmission;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

it('can render a form', function () {
    livewire(RenderedForm::class)
        ->assertStatus(200);
});

it('can build a form', function () {
    $form = createForm();

    livewire(RenderedForm::class, ['form' => $form])
        ->assertSee(['Name', 'E-mail'])
        ->assertStatus(200);
});

it('can save a form', function () {
    $form = createForm();

    $fieldKeys = array_keys($form->fields[0]);

    livewire(RenderedForm::class, ['form' => $form])
        ->assertSee(['Name', 'E-mail'])
        ->set('fields.' . $fieldKeys[0], 'John Doe')
        ->set('fields.' . $fieldKeys[1], 'email@test.xyz')
        ->call('saveData')
        ->assertHasNoErrors()
        ->assertStatus(200);

    expect(FormSubmission::first())
        ->form_id->toBe($form->id)
        ->fields->toBe($form->fields)
        ->data->toHaveData([
            [
                'key' => $fieldKeys[0],
                'value' => 'John Doe',
            ],
            [
                'key' => $fieldKeys[1],
                'value' => 'email@test.xyz',
            ],
        ]);
});

it('can send an email', function () {
    config([
        'filament-form-architect.enable-admin-mails' => true,
    ]);
    Mail::fake();

    $form = createForm();

    $fieldKeys = array_keys($form->fields[0]);

    livewire(RenderedForm::class, ['form' => $form])
        ->assertSee(['Name', 'E-mail'])
        ->set('fields.' . $fieldKeys[0], 'John Doe')
        ->set('fields.' . $fieldKeys[1], 'email@test.xyz')
        ->call('saveData')
        ->assertHasNoErrors()
        ->assertStatus(200);

    Mail::assertSent(function (SendFormSubmission $mail) use ($form) {
        return $mail->hasTo($form->getEmailsFor('to'))
            && $mail->hasFrom($form->getFromEmail())
            && $mail->hasSubject($form->email_subject)
            && $mail->assertSeeInHtml($form->email_body, false)
            && $mail->assertSeeInHtml('>Name</td>', false)
            && $mail->assertSeeInHtml('>John Doe</td>', false)
            && $mail->assertSeeInHtml('>E-mail</td>', false)
            && $mail->assertSeeInHtml('>email@test.xyz</td>', false)
            && $mail->assertSeeInHtml('<a href="' . FormResource::getUrl('submissions', ['record' => $form]) . '" target="_blank">View in CMS</a>', false);
    });
});

it('will not send an email if disabled', function () {
    config([
        'filament-form-architect.enable-admin-mails' => false,
    ]);
    Mail::fake();

    $form = createForm();

    $fieldKeys = array_keys($form->fields[0]);

    livewire(RenderedForm::class, ['form' => $form])
        ->assertSee(['Name', 'E-mail'])
        ->set('fields.' . $fieldKeys[0], 'John Doe')
        ->set('fields.' . $fieldKeys[1], 'email@test.xyz')
        ->call('saveData')
        ->assertHasNoErrors()
        ->assertStatus(200);

    Mail::assertNothingSent();
});

function createForm()
{
    return Form::factory()->create([
        'fields' => [
            [
                (string) Str::uuid() => [
                    'type' => 'TextInputBlock',
                    'width' => 12,
                    'data' => [
                        'hide_placeholder' => false,
                        'is_required' => true,
                        'en' => [
                            'online' => true,
                            'label' => 'Name',
                            'gdpr_notice' => null,
                        ],
                        'working_title' => 'Name',
                        'type' => null,
                    ],
                ],
                (string) Str::uuid() => [
                    'type' => 'TextInputBlock',
                    'width' => 12,
                    'data' => [
                        'hide_placeholder' => false,
                        'is_required' => true,
                        'en' => [
                            'online' => true,
                            'label' => 'E-mail',
                            'gdpr_notice' => null,
                        ],
                        'working_title' => 'E-mail',
                        'type' => null,
                    ],
                ],
            ],
        ],
        'email_to' => [
            [
                'email' => 'from@whoownsthezebra.be',
                'type' => 'to',
            ],
        ],
    ]);
}
