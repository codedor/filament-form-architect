<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\LivewireForms\Fields\Field;
use Codedor\LivewireForms\Fields\FileField;
use Codedor\MediaLibrary\Models\Attachment;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Infolists\Components\TextEntry;

class FileInputBlock extends BaseBlock
{
    protected ?string $name = 'File upload field';

    public static function toLivewireForm(string $uuid, array $data, array $translated): Field
    {
        return FileField::make($uuid)
            ->label($translated['label'])
            ->required($data['is_required'] ?? false)
            ->rules($data['is_required'] ? 'required' : null)
            ->disk('private');
    }

    public static function toInfolist(string $name, mixed $value)
    {
        $attachment = Attachment::find($value);

        return TextEntry::make($name)->getStateUsing(
            $attachment->url,
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
