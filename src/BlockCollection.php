<?php

namespace Codedor\FormArchitect;

use Illuminate\Contracts\View\View;

/**
 * @template TKey of array-key
 * @template TValue of \Codedor\FilamentArchitect\Filament\Architect\BaseBlock
 *
 * @extends \Codedor\FilamentArchitect\BlockCollection<TKey, TValue>
 */
class BlockCollection extends \Codedor\FilamentArchitect\BlockCollection
{
    /**
     * Run a map over each of the items.
     *
     * @return static<TKey, TValue>
     */
    public function fromConfig(): self
    {
        $config = (array) config('filament-form-architect.default-blocks', []);

        collect($config)
            ->keys()
            ->each(fn ($blockClass) => $this->add($blockClass::make()));

        return $this->filter()->unique();
    }
}
