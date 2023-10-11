<?php

namespace Codedor\FormArchitect\Filament\Resources\Pages;

use Codedor\FormArchitect\Filament\Resources\FormResource;
use Codedor\TranslatableTabs\Resources\Traits\HasTranslations;
use Filament\Resources\Pages\CreateRecord;

class CreateForm extends CreateRecord
{
    use HasTranslations;

    protected static string $resource = FormResource::class;
}
