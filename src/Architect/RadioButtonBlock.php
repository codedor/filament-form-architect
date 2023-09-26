<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\LocaleCollection\Facades\LocaleCollection;
use Codedor\LocaleCollection\Locale;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;

class RadioButtonBlock extends BaseBlock
{
    public function schema(): array
    {
        $labels = LocaleCollection::map(
            fn (Locale $locale) => TextInput::make("label.{$locale->locale()}")
                ->label("Label ({$locale->locale()})")
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
                        ]),
                    Tabs\Tab::make('Content')
                        ->schema([
                            Repeater::make('options')
                                ->schema([
                                    TextInput::make('key'),
                                    ...$labels,
                                ]),
                        ]),
                ]),
        ];
    }
}
