<?php

namespace Wotz\FormArchitect\Filament\Resources\Pages;

use Filament\Resources\Pages\ListRecords;
use Wotz\FormArchitect\Filament\Resources\FormSubmissionResource;

class ViewForm extends ListRecords
{
    protected static string $resource = FormSubmissionResource::class;
}
