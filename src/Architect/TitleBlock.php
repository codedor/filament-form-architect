<?php

namespace Codedor\FormArchitect\Architect;

use App\Filament\Tiptap\LinkAction;
use App\Filament\Tiptap\MediaAction;
use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\Title;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\HtmlString;

class TitleBlock extends BaseBlock
{
    protected ?string $name = 'Title component';

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
                            'h2' => 'h2',
                            'h3' => 'h3',
                            'h4' => 'h4',
                            'p' => 'p',
                            'small' => 'small',
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
