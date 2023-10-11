<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class TextInputBlock extends BaseBlock
{
    protected ?string $name = 'Text field';

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->defaultFields([
                    Select::make('type')
                        ->options([
                            'text' => 'Text',
                            'number' => 'Number',
                        ]),

                    Checkbox::make('is_required'),
                ])
                ->translatableFields(fn () => [
                    TextInput::make('label')
                        ->required(),

                    TextInput::make('gdpr_notice')
                        ->label('GDPR Notice')
                        ->helperText('This will explain why you need this information and how you will use it.'),
                ]),
        ];
    }
}
