<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\RadioGroup;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Infolists\Components\TextEntry;

class RadioButtonBlock extends BaseBlock
{
    protected ?string $name = 'Radio buttons';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        $options = collect($translated['options'])
            ->mapWithKeys(fn ($option) => [$option => $option])
            ->toArray();

        return RadioGroup::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'required' : null)
            ->options($options);
    }

    public static function toInfolist(string $name, mixed $value)
    {
        return TextEntry::make($name)
            ->getStateUsing(fn () => $value);
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
