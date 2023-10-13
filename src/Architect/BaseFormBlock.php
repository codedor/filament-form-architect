<?php

namespace Codedor\FormArchitect\Architect;

use Codedor\FilamentArchitect\Filament\Architect\BaseBlock;
use Codedor\LivewireForms\Fields\Field;

class BaseFormBlock extends BaseBlock
{
    public function schema(): array
    {
        return [];
    }

    public static function extraParameters(Field $field): Field
    {
        return $field;
    }
}
