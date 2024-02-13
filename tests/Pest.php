<?php

use Codedor\FormArchitect\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

expect()->extend('toHaveData', function (array $data) {
    foreach ($data as $item) {
        $this->toContain($item);
    }

    return $this;
});
