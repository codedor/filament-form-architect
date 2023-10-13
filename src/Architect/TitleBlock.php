<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\Title;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\HtmlString;

class TitleBlock extends BaseFormBlock
{
    protected ?string $name = 'Static text';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return Title::make($uuid)
            ->label(new HtmlString(
                parse_link_picker_json(
                    $translated['label']
                )
            ))
            ->tag($data['tag'] ?? 'h2')
            ->headingClass($data['tag'] ?? 'h2');
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistInQueryString(false)
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
                    TiptapEditor::make('label')
                        ->tools(['link'])
                        ->required(fn (Get $get) => $get('online')),

                    Toggle::make('online'),
                ]),
        ];
    }
}
