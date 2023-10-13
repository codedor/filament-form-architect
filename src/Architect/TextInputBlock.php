<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\TextField;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\HtmlString;

class TextInputBlock extends BaseFormBlock
{
    protected ?string $name = 'Text field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return TextField::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'required' : null)
            ->gdprNotice(new HtmlString($translated['gdpr_notice'] ?? null))
            ->type($data['type'] ?? 'text')
            ->validationMessages([
                'required' => __('validation.required', [
                    'attribute' => $translated['label'],
                ]),
            ]);
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistInQueryString(false)
                ->defaultFields([
                    Select::make('type')
                        ->selectablePlaceholder(false)
                        ->options([
                            'text' => 'Text',
                            'email' => 'E-mail',
                            'number' => 'Number',
                        ]),

                    Toggle::make('is_required'),
                ])
                ->translatableFields(fn () => [
                    TextInput::make('label')
                        ->required(fn (Get $get) => $get('online')),

                    TiptapEditor::make('gdpr_notice')
                        ->label('GDPR Notice')
                        ->helperText('This will explain why you need this information and how you will use it.'),

                    Toggle::make('online'),
                ]),
        ];
    }
}
