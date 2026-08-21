<?php

namespace Codedor\FormArchitect\Architect;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;
use Wotz\LivewireForms\Fields\Field;
use Wotz\LivewireForms\Fields\Title;
use Wotz\TranslatableTabs\Forms\TranslatableTabs;

class TitleBlock extends BaseFormBlock
{
    protected ?string $name = 'Static text';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return Title::make($uuid)
            ->label(new HtmlString($translated['label']))
            ->tag($data['tag'] ?? 'h2')
            ->headingClass($data['tag'] ?? 'h2');
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistTabInQueryString(null)
                ->defaultFields([
                    Select::make('tag')
                        ->label('Styling')
                        ->selectablePlaceholder(false)
                        ->options([
                            'h2' => 'Largest (h2)',
                            'h3' => 'Large (h3)',
                            'h4' => 'Medium (h4)',
                            'p' => 'Paragraph (p)',
                            'small' => 'Small text',
                        ]),
                ])
                ->translatableFields(fn () => [
                    RichEditor::make('label')
                        ->toolbarButtons(['link'])
                        ->required(fn (Get $get) => $get('online')),

                    Toggle::make('online'),
                ]),
        ];
    }
}
