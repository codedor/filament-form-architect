<?php

namespace Codedor\FormArchitect\Filament\Resources\Pages;

use Codedor\FormArchitect\Filament\Resources\FormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Guava\FilamentDrafts\Admin\Resources\Pages\List\Draftable;

class ListForms extends ListRecords
{
    use Draftable;

    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
