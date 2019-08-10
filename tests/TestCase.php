<?php

namespace Dstuchbury\LaravelLogEnhancer\Test;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extenda Orchestra
{
    protected function getPackageProviders($app)
    {
        return ['Dstuchbury\:aravelLogEnhancer\LaravelLogEnhancerServiceProvider'];
    }

    /**
    * Define environment setup
    *
    * @param \Illuminate\Foundation\Application     $app
    * @return void
    */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('logging.channels.stack.tap', [\Dstuchbury\LaravelLogEnhancer\LogEnhancer::class];
    }
}
