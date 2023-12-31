<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\SelectField;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;

class SelectInputBlock extends BaseFormBlock
{
    protected ?string $name = 'Dropdown select field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        $options = collect($translated['options'] ?? [])
            ->mapWithKeys(fn ($option) => [$option => $option])
            ->toArray();

        return SelectField::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'required' : null)
            ->gdprNotice(new HtmlString($translated['gdpr_notice'] ?? null))
            ->options($options)
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
                    Toggle::make('is_required'),
                ])
                ->translatableFields(fn () => [
                    TextInput::make('label')
                        ->required(fn (Get $get) => $get('online')),

                    TextInput::make('gdpr_notice')
                        ->label('GDPR Notice')
                        ->helperText('This will explain why you need this information and how you will use it.'),

                    Repeater::make('options')
                        ->simple(TextInput::make('label')),

                    Toggle::make('online'),
                ]),
        ];
    }
}
