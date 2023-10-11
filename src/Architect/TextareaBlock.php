<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\TextareaField;
use Codedor\LivewireForms\Fields\TextField;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;

class TextareaBlock extends BaseBlock
{
    protected ?string $name = 'Textarea field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        // TODO: what to do with max value??
        return TextareaField::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'required' : null)
            ->type('email');
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistInQueryString(false)
                ->defaultFields([
                    TextInput::make('max')
                        ->label('Max characters')
                        ->numeric(),

                    Toggle::make('is_required'),
                ])
                ->translatableFields(fn () => [
                    TextInput::make('label')
                        ->required(fn (Get $get) => $get('online')),

                    TextInput::make('gdpr_notice')
                        ->label('GDPR Notice')
                        ->helperText('This will explain why you need this information and how you will use it.'),
                ]),
        ];
    }
}
