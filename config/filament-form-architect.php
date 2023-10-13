<?php

use Codedor\FormArchitect\Architect;

return [
    'enable-submission-field' => false,
    'default-row-attributes' => [
        'divClass' => 'row',
    ],
    'default-blocks' => [
        Architect\TitleBlock::class => [],
        Architect\TextInputBlock::class => [],
        Architect\TextareaBlock::class => [],
        Architect\RadioButtonBlock::class => [],
        Architect\FileInputBlock::class => [],
        Architect\CheckboxBlock::class => [],
    ],
];
