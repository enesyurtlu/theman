<?php

namespace enesyurtlu\theman\Tests;

use enesyurtlu\theman\Facades\theman;
use enesyurtlu\theman\TheManServiceProvider;
use Orchestra\Testbench\TestCase;

class themanTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [TheManServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'theman' => theman::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
