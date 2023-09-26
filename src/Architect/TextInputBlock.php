<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\LocaleCollection\Facades\LocaleCollection;
use Codedor\LocaleCollection\Locale;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;

class TextInputBlock extends BaseBlock
{
    public function schema(): array
    {
        $labels = LocaleCollection::map(fn (Locale $locale) =>
        Fieldset::make($locale->locale())
            ->schema([
                TextInput::make("{$locale->locale()}.label")
                    ->label("Label"),
                TextInput::make("{$locale->locale()}.gdpr_notice")
                    ->label("GDPR Notice"),
            ])
        );

        return [
            Tabs::make()
                ->tabs([
                    Tabs\Tab::make('Settings')
                        ->schema([
                            Select::make('layout')
                                ->options([
                                    'full' => 'Full',
                                    'half' => 'Half',
                                ]),
                            Select::make('type')
                                ->options([
                                    'text' => 'Text',
                                    'number' => 'Number',
                                ]),
                            Checkbox::make('is_required'),
                        ]),
                    Tabs\Tab::make('Content')
                        ->schema($labels->toArray())
                ]),
        ];
    }
}
