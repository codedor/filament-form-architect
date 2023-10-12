<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\TextField;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;

class TextInputBlock extends BaseBlock
{
    protected ?string $name = 'Text field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return TextField::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'required' : null)
            ->type($data['type'] ?? 'text')
            ->validationMessages([
                "fields.{$uuid}.required" => __('validation.required', [
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

                    TextInput::make('gdpr_notice')
                        ->label('GDPR Notice')
                        ->helperText('This will explain why you need this information and how you will use it.'),

                    Toggle::make('online'),
                ]),
        ];
    }
}
