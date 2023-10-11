<?php

namespace Codedor\FormArchitect\Livewire;

use Codedor\FormArchitect\Models\Form;
use Codedor\FormArchitect\Models\FormSubmission;
use Codedor\LivewireForms\Fields\Button;
use Codedor\LivewireForms\Form as LivewireFormsForm;
use Codedor\LivewireForms\FormController;

class RenderedForm extends FormController
{
    public ?Form $formModel;

    public string $modelClass = FormSubmission::class;

    public function mount(
        ?string $component = null,
        ?string $formClass = null,
        ?Form $form = null,
    ) {
        $this->formClass = 'dynamic';
        $this->formModel = $form;

        parent::mount();
    }

    public function saveData()
    {
        $fields = $this->fields;
        unset($fields['locale']);

        $this->savedModel = $this->modelClass::create([
            'form_id' => $this->formModel->id,
            'locale' => app()->getLocale(),
            'fields' => $fields,
        ]);
    }

    public function resetForm()
    {
        // Don't do this
    }

    public function getForm()
    {
        return (new class($this->formModel) extends LivewireFormsForm {
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
        });
    }
}
