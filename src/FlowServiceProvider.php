<?php

namespace Laravel\Flow;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Laravel\Flow\Console\FlowMakeCommand;
use Laravel\Flow\Console\FlowQueueCommand;

class FlowServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            $this->commands([
                FlowMakeCommand::class,
                FlowQueueCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/flow.php' => config_path('flow.php'),
            ], 'flow-config');
        }

        $this->registerEventListeners();

        $this->registerScheduledJob();
    }

    private function registerEventListeners()
    {
        $this->app->afterResolving(Filesystem::class, function () {
            $flow = $this->app->make(FlowEvents::class);

            if ($flow->isCached()) {
                $flow->loadFromCache();
            } else {
                $flow->compile();
            }

            $flow->listen();
        });
    }

    private function registerScheduledJob()
    {
        $this->app->booted(function () {
            /**
             * @var $schedule Schedule
             */
            $schedule = $this->app->make(Schedule::class);

            $schedule->command('flow:queue')->everyMinute()->onOneServer();
        });
    }
}
