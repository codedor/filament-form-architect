<?php

namespace Codedor\FormArchitect\Filament\Resources\Pages;

use Codedor\FormArchitect\Filament\Resources\FormResource;
use Codedor\FormArchitect\Filament\Resources\FormSubmissionResource;
use Codedor\FormArchitect\Models\Form;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListFormSubmissions extends ListRecords
{
    protected static string $resource = FormSubmissionResource::class;

    public Form $record;

    public function mount(?Form $record = null): void
    {
        parent::mount();
    }

    public function getFilteredTableQuery(): Builder
    {
        return parent::getFilteredTableQuery()->where('form_id', $this->record->id);
    }

    public function getBreadcrumbs(): array
    {
        $resource = FormResource::class;

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
            $resource::getUrl('edit', ['record' => $this->record]) => ($this->record->name ?? $this->record->id),
            ...(filled($breadcrumb = $this->getBreadcrumb()) ? [$breadcrumb] : []),
        ];

        if (filled($cluster = static::getCluster())) {
            return $cluster::unshiftClusterBreadcrumbs($breadcrumbs);
        }

        return $breadcrumbs;
    }
}
