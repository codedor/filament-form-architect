<?php

namespace Codedor\FormArchitect\Filament\Resources;

use Codedor\FilamentArchitect\Filament\Fields\ArchitectInput;
use Codedor\FormArchitect\Architect\RadioButtonBlock;
use Codedor\FormArchitect\Architect\TextInputBlock;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Guava\FilamentDrafts\Admin\Resources\Concerns\Draftable;

class FormResource extends Resource
{
    use Draftable;

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

                        ArchitectInput::make('fields')
                            ->blocks([
                                RadioButtonBlock::make()->toFilament(),
                                TextInputBlock::make()->toFilament(),
                            ]),
                    ])
                    ->translatableFields(fn (string $locale) => [
                        Forms\Components\TextInput::make('email_subject'),

                        TiptapEditor::make('email_body'),

                        Forms\Components\Checkbox::make('online'),
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
}
