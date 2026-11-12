<?php

namespace Wotz\FormArchitect\Fields;

use Wotz\LivewireForms\Fields\Spacer as BaseSpacer;

/**
 * The livewire-forms spacer renders a line break; this one takes a height.
 */
class Spacer extends BaseSpacer
{
    public $component = 'filament-form-architect::fields.spacer';
}
