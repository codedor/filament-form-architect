<?php

namespace Codedor\FormArchitect\Filament\Resources\Pages;

use Codedor\FormArchitect\Filament\Resources\FormSubmissionResource;
use Codedor\FormArchitect\Models\Form;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListFormSubmissions extends ListRecords
{
    protected static string $resource = FormSubmissionResource::class;

    public Form $record;

    public function mount(null|Form $record = null): void
    {
        parent::mount();
    }

    public function getFilteredTableQuery(): Builder
    {
        return parent::getFilteredTableQuery()->where('form_id', $this->record->id);
    }
}
