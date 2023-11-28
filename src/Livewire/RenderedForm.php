<?php

namespace Codedor\FormArchitect\Livewire;

use Codedor\FormArchitect\Models\Form;
use Codedor\FormArchitect\Models\FormSubmission;
use Codedor\LivewireForms\Fields\Button;
use Codedor\LivewireForms\Form as LivewireFormsForm;
use Codedor\LivewireForms\FormController;
use Illuminate\Support\HtmlString;

class RenderedForm extends FormController
{
    public ?Form $formModel;

    public ?string $redirectTo = null;

    public string $modelClass = FormSubmission::class;

    public function mount(
        string $component = null,
        string $formClass = null,
        Form $form = null,
        string $redirectTo = null,
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
            public function __construct(public Form $formModel)
            {
                parent::__construct();
            }

            public function fields()
            {
                return [
                    ...$this->formModel->getLivewireFormFields(),
                    Button::make(__('form.submit')),
                ];
            }
        };
    }
}
