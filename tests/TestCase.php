<?php

namespace Laravel\Flow\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as Laravel;
use Laravel\Flow\FlowServiceProvider;

abstract class TestCase extends Laravel
{
    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $app->register(FlowServiceProvider::class);

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}