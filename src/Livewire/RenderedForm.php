<?php

namespace Wotz\FormArchitect\Livewire;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Wotz\FormArchitect\Mail\SendFormSubmission;
use Wotz\FormArchitect\Models\Form;
use Wotz\FormArchitect\Models\FormSubmission;
use Wotz\LivewireForms\Fields\Button;
use Wotz\LivewireForms\Form as LivewireFormsForm;
use Wotz\LivewireForms\FormController;

class RenderedForm extends FormController
{
    public ?Form $formModel;

    public ?string $redirectTo = null;

    public string $modelClass = FormSubmission::class;

    public function mount(
        ?string $component = null,
        ?string $formClass = null,
        ?Form $form = null,
        ?string $redirectTo = null,
    ) {
        $this->formClass = 'dynamic';
        $this->formModel = $form;
        $this->redirectTo = $redirectTo;

        parent::mount('filament-form-architect::livewire.rendered-form');
    }

    public function saveData()
    {
        $fields = $this->fields;

        unset($fields['locale']);

        $this->savedModel = $this->modelClass::create([
            'form_id' => $this->formModel->id,
            'locale' => app()->getLocale(),
            'fields' => $this->formModel->fields,
            'data' => collect($fields)->map(fn ($value, $key) => [
                'key' => $key,
                'value' => $value,
            ])->values(),
        ]);

        if (! Form::adminEmailsDisabled()) {
            Mail::to($this->formModel->getEmailsFor('to'))
                ->cc($this->formModel->getEmailsFor('cc'))
                ->bcc($this->formModel->getEmailsFor('bcc'))
                ->send(new SendFormSubmission($this->savedModel, $this->formModel));
        }
    }

    public function successMessage()
    {
        if ($this->redirectTo) {
            return redirect()->to($this->redirectTo)
                ->with('completion_message', $this->formModel->completion_message);
        }

        session()->flash(
            'message',
            new HtmlString($this->formModel->completion_message)
        );

        $this->dispatch('form-saved');
    }

    public function resetForm()
    {
        // Don't do this
    }

    public function getForm()
    {
        return new class($this->formModel) extends LivewireFormsForm
        {
            public function __construct(public ?Form $formModel)
            {
                parent::__construct();
            }

            public function fields()
            {
                if (! $this->formModel) {
                    return [];
                }

                return [
                    ...$this->formModel->getLivewireFormFields(),
                    Button::make(__('form.submit')),
                ];
            }
        };
    }
}
