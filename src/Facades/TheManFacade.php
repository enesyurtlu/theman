<?php

namespace enesyurtlu\TheMan\Facades;

use Illuminate\Support\Facades\Facade;

class TheManFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TheMan';
    }
}
