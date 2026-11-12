<?php

namespace Wotz\FormArchitect\Architect;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Wotz\FormArchitect\Fields\Spacer;
use Wotz\LivewireForms\Fields\Field;
use Wotz\TranslatableTabs\Forms\TranslatableTabs;

class SpacerBlock extends BaseFormBlock
{
    public const DEFAULT_HEIGHT = 25;

    protected ?string $name = 'Spacer';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return Spacer::make($uuid)
            ->height((int) ($data['height'] ?? self::DEFAULT_HEIGHT));
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistTabInQueryString(null)
                ->defaultFields([
                    TextInput::make('height')
                        ->label('Height (px)')
                        ->numeric()
                        ->minValue(0)
                        ->default(self::DEFAULT_HEIGHT)
                        ->required(),
                ])
                ->translatableFields(fn () => [
                    // Like the other blocks, it only renders in the languages it is online in
                    Toggle::make('online')
                        ->default(true),
                ]),
        ];
    }
}
