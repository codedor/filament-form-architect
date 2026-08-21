<?php

namespace Codedor\FormArchitect\Architect;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;
use Wotz\LivewireForms\Fields\CheckboxField;
use Wotz\LivewireForms\Fields\Field;
use Wotz\TranslatableTabs\Forms\TranslatableTabs;

class CheckboxBlock extends BaseFormBlock
{
    protected ?string $name = 'Checkbox field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return CheckboxField::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'accepted' : null)
            ->gdprNotice(new HtmlString($translated['gdpr_notice'] ?? null))
            ->validationMessages([
                'accepted' => __('validation.accepted', [
                    'attribute' => $translated['label'],
                ]),
            ]);
    }

    public static function toInfolist(string $name, mixed $value)
    {
        return TextEntry::make($name)->getStateUsing(fn () => $value ? 'Yes' : 'No');
    }

    public static function toExcelExport(mixed $value): string
    {
        return $value ? 'Yes' : 'No';
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistTabInQueryString(null)
                ->defaultFields([
                    Toggle::make('is_required'),
                ])
                ->translatableFields(fn () => [
                    RichEditor::make('label')
                        ->required(fn (Get $get) => $get('online'))
                        ->toolbarButtons(config('filament-form-architect.checkbox-toolbar-buttons', ['bold', 'italic', 'link'])),

                    TextInput::make('gdpr_notice')
                        ->label('GDPR Notice')
                        ->helperText('This will explain why you need this information and how you will use it.'),

                    Toggle::make('online'),
                ]),
        ];
    }
}
