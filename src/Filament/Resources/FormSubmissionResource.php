<?php

namespace Codedor\FormArchitect\Filament\Resources;

use Codedor\FormArchitect\Filament\Actions\ExportFormSubmissions;
use Codedor\FormArchitect\Models\FormSubmission;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FormSubmissionResource extends Resource
{
    protected static ?string $model = \Codedor\FormArchitect\Models\FormSubmission::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('created_at')
                ->dateTime(),

            TextEntry::make('locale'),

            Section::make()->schema(function (FormSubmission $record) {
                return $record->toInfolistSchema();
            }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('locale')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                ExportFormSubmissions::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormSubmissions::route('/'),
        ];
    }
}
