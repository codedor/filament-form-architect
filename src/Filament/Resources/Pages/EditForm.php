<?php

namespace Codedor\FormArchitect\Filament\Resources\Pages;

use Codedor\FormArchitect\Filament\Resources\FormResource;
use Codedor\TranslatableTabs\Resources\Traits\HasTranslations;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Guava\FilamentDrafts\Admin\Resources\Pages\Edit\Draftable;

class EditForm extends EditRecord
{
    use Draftable;
    use HasTranslations;

    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
