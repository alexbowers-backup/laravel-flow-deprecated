<?php

namespace Laravel\Flow;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Laravel\Flow\Console\FlowMakeCommand;
use Laravel\Flow\Console\FlowQueueCommand;

class FlowServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $this->commands([
                FlowMakeCommand::class,
                FlowQueueCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../config/flow.php' => config_path('flow.php'),
            ], 'flow-config');
        }

        $this->app->booted(function () {
            $this->registerEventListeners();

            $this->registerScheduledJob();
        });
    }

    private function registerEventListeners()
    {
        $flow = $this->app->make(FlowEvents::class);

        if ($flow->isCached()) {
            $flow->loadFromCache();
        } else {
            $flow->compile();
        }

        $flow->listen();
    }

    private function registerScheduledJob()
    {
        /**
         * @var $schedule Schedule
         */
        $schedule = $this->app->make(Schedule::class);

        $schedule->command('flow:queue')->everyMinute()->onOneServer();
    }
}
