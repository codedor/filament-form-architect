<?php

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

    livewire(RenderedForm::class, ['form' => $form])
        ->assertSee(['Name', 'E-mail'])
        ->set('fields.name', 'John Doe')
        ->set('fields.email', 'email@test.xyz')
        ->call('saveData')
        ->assertHasNoErrors()
        ->assertStatus(200);

    expect(FormSubmission::first())
        ->form_id->toBe($form->id)
        ->fields->toBe($form->fields)
        ->data->toHaveData([
            [
                'key' => 'name',
                'value' => 'John Doe',
            ],
            [
                'key' => 'email',
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

    livewire(RenderedForm::class, ['form' => $form])
        ->assertSee(['Name', 'E-mail'])
        ->set('fields.name', 'John Doe')
        ->set('fields.email', 'email@test.xyz')
        ->call('saveData')
        ->assertHasNoErrors()
        ->assertStatus(200);

    Mail::assertSent(function (SendFormSubmission $mail) use ($form) {
        return $mail->hasTo($form->getEmailsFor('to'))
            && $mail->hasFrom($form->getFromEmail())
            && $mail->hasSubject($form->email_subject)
            && $mail->assertSeeInHtml($form->email_body);
    });
});

it('will not send an email if disabled', function () {
    config([
        'filament-form-architect.enable-admin-mails' => false,
    ]);
    Mail::fake();

    $form = createForm();

    livewire(RenderedForm::class, ['form' => $form])
        ->assertSee(['Name', 'E-mail'])
        ->set('fields.name', 'John Doe')
        ->set('fields.email', 'email@test.xyz')
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
