<?php

namespace Codedor\FormArchitect\Filament\Resources\Pages;

use Codedor\FormArchitect\Filament\Resources\FormResource;
use Codedor\TranslatableTabs\Resources\Traits\HasTranslations;
use Filament\Resources\Pages\CreateRecord;
use Guava\FilamentDrafts\Admin\Resources\Pages\Create\Draftable;

class CreateForm extends CreateRecord
{
    use Draftable;
    use HasTranslations;

    protected static string $resource = FormResource::class;
}
