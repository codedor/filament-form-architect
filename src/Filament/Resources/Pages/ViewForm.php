<?php

namespace Codedor\FormArchitect\Filament\Resources\Pages;

use Codedor\FormArchitect\Filament\Resources\FormSubmissionResource;
use Filament\Resources\Pages\ListRecords;

class ViewForm extends ListRecords
{
    protected static string $resource = FormSubmissionResource::class;
}
