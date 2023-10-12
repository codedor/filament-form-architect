<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\Title;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TitleBlock extends BaseBlock
{
    protected ?string $name = 'Title component';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return Title::make($uuid)
            ->label($translated['label'])
            ->styling($data['styling']);
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistInQueryString(false)
                ->defaultFields([
                    Select::make('styling')
                        ->selectablePlaceholder(false)
                        ->options([
                            'h2' => 'h2',
                            'h3' => 'h3',
                            'h4' => 'h4',
                            'p' => 'p',
                            'small' => 'small',
                        ]),
                ])
                ->translatableFields(fn () => [
                    TextInput::make('label')
                        ->required(),

                    Toggle::make('online'),
                ]),
        ];
    }
}
