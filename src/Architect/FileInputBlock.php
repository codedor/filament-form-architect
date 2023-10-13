<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\FileField;
use Codedor\LivewireForms\Fields\MultiFileField;
use Codedor\MediaLibrary\Models\Attachment;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Infolists\Components\ViewEntry;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class FileInputBlock extends BaseFormBlock
{
    protected ?string $name = 'File upload field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        $class = ($data['multiple'] ?? false) ? MultiFileField::class : FileField::class;

        return $class::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'required' : null)
            ->gdprNotice(new HtmlString($translated['gdpr_notice'] ?? null))
            ->disk('private')
            ->validationMessages([
                "files.{$uuid}.required" => __('validation.required', [
                    'attribute' => $translated['label'],
                ]),
            ]);
    }

    public static function toInfolist(string $name, mixed $value)
    {
        return ViewEntry::make($name)->view(
            'filament-form-architect::components.infolists.file',
            ['attachments' => Collection::wrap(Attachment::find($value))->filter()]
        );
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->persistInQueryString(false)
                ->defaultFields([
                    Toggle::make('multiple')
                        ->helperText('Allow multiple files to be uploaded'),

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
