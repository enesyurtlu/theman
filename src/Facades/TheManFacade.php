<?php

namespace enesyurtlu\theman\Facades;

use Illuminate\Support\Facades\Facade;

class TheManFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TheMan';
    }
}
