<?php

namespace Wotz\FormArchitect\Filament\Actions;

use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Wotz\FormArchitect\Exports\FormSubmissionsExport;

class ExportFormSubmissions extends BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'exportFormSubmissions';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Export');

        $this->icon('heroicon-o-arrow-down-tray');

        $this->schema(fn () => [
            TextInput::make('filename')
                ->label('Filename')
                ->default('form-submissions'),

            Select::make('format')
                ->label('Format')
                ->default('xlsx')
                ->options([
                    'xlsx' => 'XLSX',
                    'csv' => 'CSV',
                ]),
        ]);

        $this->action(function (Collection $records, array $data) {
            return Excel::download(
                new FormSubmissionsExport($records),
                "{$data['filename']}.{$data['format']}"
            );
        });
    }
}
