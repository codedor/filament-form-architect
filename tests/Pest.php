<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Wotz\FormArchitect\Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

expect()->extend('toHaveData', function (array $data) {
    foreach ($data as $item) {
        $this->toContain($item);
    }

    return $this;
});
