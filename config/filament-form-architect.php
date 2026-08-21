<?php

use Wotz\FormArchitect\Architect;

return [
    'enable-submission-field' => false,
    'enable-admin-mails' => false,
    'default-row-attributes' => [
        'divClass' => 'row',
    ],
    'default-blocks' => [
        Architect\CheckboxBlock::class => [],
        Architect\CountryInputBlock::class => [],
        Architect\FileInputBlock::class => [],
        Architect\RadioButtonBlock::class => [],
        Architect\SelectInputBlock::class => [],
        Architect\TextareaBlock::class => [],
        Architect\TextInputBlock::class => [],
        Architect\TitleBlock::class => [],
    ],
    'checkbox-toolbar-buttons' => ['bold', 'italic', 'link'],
];
