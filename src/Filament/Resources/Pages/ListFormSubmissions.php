<?php

namespace Wotz\FormArchitect\Filament\Resources\Pages;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Wotz\FormArchitect\Filament\Resources\FormSubmissionResource;
use Wotz\FormArchitect\Models\Form;

class ListFormSubmissions extends ListRecords
{
    protected static string $resource = FormSubmissionResource::class;

    public ?Form $record = null;

    public function mount(?Form $record = null): void
    {
        $this->record = $record;

        parent::mount();
    }

    public function getFilteredTableQuery(): Builder
    {
        return parent::getFilteredTableQuery()
            ->when($this->record, fn (Builder $query) => $query->where('form_id', $this->record->id));
    }
}
