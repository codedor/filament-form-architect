<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\LivewireForms\Fields\CountryField;
use Codedor\LivewireForms\Fields\Field;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\HtmlString;

class CountryInputBlock extends BaseFormBlock
{
    protected ?string $name = 'Country field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return CountryField::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'required' : null)
            ->gdprNotice(new HtmlString($translated['gdpr_notice'] ?? null))
            ->default($data['default_value'] ?? null)
            ->validationMessages([
                'required' => __('validation.required', [
                    'attribute' => $translated['label'],
                ]),
            ]);
    }

    public static function toInfolist(string $name, mixed $value)
    {
        return TextEntry::make($name)
            ->getStateUsing(fn () => getCountryName($value));
    }

    public static function toExcelExport(mixed $value): string
    {
        return getCountryName($value);
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistInQueryString(false)
                ->defaultFields([
                    Select::make('default_value')
                        ->label('Default selected country')
                        ->options(fn () => getCountryList()),

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
