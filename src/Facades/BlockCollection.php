<?php

namespace Codedor\FormArchitect\Facades;

use Illuminate\Support\Facades\Facade;

class BlockCollection extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Codedor\FormArchitect\BlockCollection::class;
    }
}
