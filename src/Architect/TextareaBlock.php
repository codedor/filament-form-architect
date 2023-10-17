<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\TextareaField;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;

class TextareaBlock extends BaseFormBlock
{
    protected ?string $name = 'Large text field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        $max = $data['max'] ?? null;

        return TextareaField::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules([
                $data['is_required'] ? 'required' : null,
                $max ? "max:{$max}" : null,
            ])
            ->max($max ?? null)
            ->gdprNotice(new HtmlString($translated['gdpr_notice'] ?? null))
            ->placeholder(($data['hide_placeholder'] ?? false) ? '' : ($translated['label'] ?? null))
            ->validationMessages([
                'required' => __('validation.required', [
                    'attribute' => $translated['label'],
                ]),
                'string' => __('validation.max', [
                    'attribute' => $translated['label'],
                    'max' => $max,
                ]),
            ]);
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistInQueryString(false)
                ->defaultFields([
                    TextInput::make('max')
                        ->label('Maximum allowed characters')
                        ->numeric(),

                    Toggle::make('hide_placeholder')
                        ->label('Hide the placeholder inside the field'),

                    Toggle::make('is_required'),
                ])
                ->translatableFields(fn () => [
                    TextInput::make('label')
                        ->required(fn (Get $get) => $get('online')),

                    TextInput::make('gdpr_notice')
                        ->label('GDPR Notice')
                        ->helperText('This will explain why you need this information and how you will use it.'),

                    Toggle::make('online'),
                ]),
        ];
    }
}
