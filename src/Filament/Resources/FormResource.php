<?php

namespace Wotz\FormArchitect\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Wotz\FilamentMailTemplates\Facades\MailTemplateFallbacks;
use Wotz\FormArchitect\Filament\Fields\FormArchitectInput;
use Wotz\FormArchitect\Models\Form;
use Wotz\FormArchitect\Models\Form as ModelsForm;
use Wotz\TranslatableTabs\Forms\TranslatableTabs;

class FormResource extends Resource
{
    protected static ?string $model = Form::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $modelLabel = 'Custom Form';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make()
                    ->icon('heroicon-o-check-circle')
                    ->defaultFields([
                        TextInput::make('name')
                            ->label('Form name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email_from')
                            ->label('Admin mail sender address')
                            ->helperText(
                                'If left empty, the sites default mail will be used: ' .
                                MailTemplateFallbacks::getFromMail()
                            )
                            ->email()
                            ->maxLength(255)
                            ->hidden(ModelsForm::adminEmailsDisabled()),

                        Repeater::make('email_to')
                            ->helperText('If left empty, the sites default e-mail will be used.')
                            ->label('Admin mail recipients')
                            ->schema([
                                Grid::make()->schema([
                                    TextInput::make('email')
                                        ->required(),

                                    Select::make('type')
                                        ->required()
                                        ->options([
                                            'to' => 'Normal',
                                            'cc' => 'CC',
                                            'bcc' => 'BCC',
                                        ]),
                                ]),
                            ])
                            ->hidden(ModelsForm::adminEmailsDisabled()),

                        TextInput::make('max_submissions')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->helperText('Enter 0 to allow unlimited submissions.')
                            ->hidden(ModelsForm::maxSubmissionsDisabled()),

                        FormArchitectInput::make('fields'),
                    ])
                    ->translatableFields(fn () => [
                        TextInput::make('email_subject')
                            ->label('E-mail subject')
                            ->hidden(ModelsForm::adminEmailsDisabled()),

                        RichEditor::make('email_body')
                            ->label('E-mail body')
                            ->hidden(ModelsForm::adminEmailsDisabled()),

                        RichEditor::make('completion_message')
                            ->label('After submit completion message')
                            ->helperText('This message will be shown to the user after submitting the form.'),

                        RichEditor::make('max_submissions_message')
                            ->label('Maximum submissions message')
                            ->helperText('This message will be shown to the user when the maximum amount of submissions has been reached.')
                            ->hidden(ModelsForm::maxSubmissionsDisabled()),
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

                Tables\Columns\TextColumn::make('max_submissions')
                    ->numeric()
                    ->sortable()
                    ->hidden(ModelsForm::maxSubmissionsDisabled()),

                Tables\Columns\TextColumn::make('submissions')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->submissions()->count()),
            ])
            ->recordActions([
                Action::make('submissions')
                    ->url(fn ($record): string => self::getUrl('submissions', [$record]))
                    ->color('gray')
                    ->icon('heroicon-s-eye'),

                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
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
