<?php

namespace Codedor\FormArchitect\Filament\Fields;

use Closure;
use Codedor\FilamentArchitect\Filament\Fields\ArchitectInput;

class FormArchitectInput extends ArchitectInput
{
    public null|int|Closure $maxFieldsPerRow = 3;

    public Closure|bool $hasTemplates = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasPreview(false);

        $this->blocks(
            collect(config('filament-form-architect.default-blocks', []))
                ->keys()
                ->map(fn (string $class) => $class::make())
                ->unique()
                ->filter()
        );
    }
}
