<?php

namespace Codedor\FormArchitect\Filament\Resources;

use Codedor\FormArchitect\Architect\EmailInputBlock;
use Codedor\FormArchitect\Architect\FileInputBlock;
use Codedor\FormArchitect\Architect\RadioButtonBlock;
use Codedor\FormArchitect\Architect\TextareaBlock;
use Codedor\FormArchitect\Architect\TextBlock;
use Codedor\FormArchitect\Architect\TextInputBlock;
use Codedor\FormArchitect\Architect\TitleBlock;
use Codedor\FormArchitect\Filament\Fields\FormArchitectInput;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;

class FormResource extends Resource
{
    protected static ?string $model = \Codedor\FormArchitect\Models\Form::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TranslatableTabs::make()
                    ->defaultFields([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('max_submissions')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->helperText('0 is unlimited'),

                        FormArchitectInput::make('fields')
                            ->blocks([
                                TitleBlock::make(),
                                TextInputBlock::make(),
                                TextareaBlock::make(),
                                RadioButtonBlock::make(),
                                FileInputBlock::make(),
                            ]),
                    ])
                    ->translatableFields(fn () => [
                        Forms\Components\TextInput::make('email_subject'),

                        TiptapEditor::make('email_body'),

                        Forms\Components\Toggle::make('online'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('max_submissions')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('submissions')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->submissions()->count()),
            ])
            ->actions([
                Tables\Actions\Action::make('submissions')
                    ->url(fn ($record): string => self::getUrl('submissions', [$record]))
                    ->color('gray')
                    ->icon('heroicon-s-eye'),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()->schema([
                TextEntry::make('name')->label('Form'),

                TextEntry::make('submissions_count')
                    ->label('Amount of submissions')
                    ->getStateUsing(fn ($record) => $record->submissions()->count()),

                TextEntry::make('last_submission_at')
                    ->label('Last submission received on')
                    ->getStateUsing(fn ($record) => $record->submissions()->latest()->first()?->created_at),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
            'submissions' => Pages\ListFormSubmissions::route('/{record}/submissions'),
        ];
    }
}
