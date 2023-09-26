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
        collect((array) config('filament-form-architect.default-blocks', []))
            ->each(function ($blockClass): void {
                /** @var TValue $class */
                $class = $blockClass::make();

                $this->put($class->getName(), $class);
            });

        return $this;
    }

    public function render(array $blocks): View
    {
        return view('filament-form-architect::overview')
            ->with(
                'blocks',
                collect($blocks)
                    ->filter(fn (array $blockData) => $this->has($blockData['type']))
                    ->map(function (array $blockData) {
                        /** @var TValue $block */
                        $block = $this->get($blockData['type']);
                        $block = clone $block;

                        return $block->data($blockData)->render();
                    })
            );
    }
}
