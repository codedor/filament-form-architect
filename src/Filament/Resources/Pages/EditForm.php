<?php

namespace Wotz\FormArchitect\Filament\Resources\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Wotz\FormArchitect\Filament\Resources\FormResource;

class EditForm extends EditRecord
{
    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
