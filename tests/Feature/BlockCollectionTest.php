<?php

use Codedor\FormArchitect\Architect\RadioButtonBlock;
use Codedor\FormArchitect\Architect\TextInputBlock;
use Filament\Forms\Components\Builder\Block;

it('can fill collection from original config', function () {
    $collection = \Codedor\FormArchitect\Facades\BlockCollection::all();

    expect($collection)
        ->toHaveCount(2)
        ->sequence(
            function ($block, $key) {
                $block->toBeInstanceOf(RadioButtonBlock::class);
                $key->toBe('RadioButtonBlock');
            },
            function ($block, $key) {
                $block->toBeInstanceOf(TextInputBlock::class);
                $key->toBe('TextInputBlock');
            },
        );
});

it('can return the filament blocks', function () {
    $collection = new \Codedor\FormArchitect\BlockCollection();

    $collection->put('TextInputBlock', new TextInputBlock());

    $blocks = $collection->filamentBlocks();

    expect($blocks)
        ->toHaveKey('TextInputBlock')
        ->sequence(
            fn ($block) => $block
                ->toBeInstanceOf(Block::class)
                ->getName()->toBe('TextInputBlock')
                ->getChildComponents()
                ->toHaveCount(1)
                ->sequence(fn ($field) => $field
                    ->toBeInstanceOf(\Filament\Forms\Components\Tabs::class)
                )
        );
});
