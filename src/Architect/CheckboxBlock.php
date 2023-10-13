<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\LivewireForms\Fields\CheckboxField;
use Codedor\LivewireForms\Fields\Field;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;

class CheckboxBlock extends BaseFormBlock
{
    protected ?string $name = 'Checkbox field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return CheckboxField::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'accepted' : null)
            ->validationMessages([
                "fields.{$uuid}.accepted" => __('validation.accepted', [
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

                    Toggle::make('online'),
                ]),
        ];
    }
}
