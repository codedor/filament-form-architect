<?php

namespace Codedor\FormArchitect\Livewire;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class EditModal extends Component implements HasForms
{
    use InteractsWithForms;

    public string $statePath;
    public array $state;
    public array $arguments;

    public function mount(array $arguments)
    {
        $this->arguments = $arguments;
        $this->state = $arguments['block']['data'] ?? [];
        $this->form->fill($this->state);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Group::make()
                ->statePath('state')
                ->schema([
                    TextInput::make('working_title')
                        ->helperText('This is purely to help you identify the block in the list of blocks.'),

                    ...(new $this->arguments['block']['type'])->schema()
                ])
        ]);
    }

    public function getFormData()
    {
        return $this->form->getState();
    }

    public function render()
    {
        return view('filament-form-architect::livewire.edit-modal');
    }
}