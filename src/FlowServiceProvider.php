<?php

namespace Laravel\Flow;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laravel\Flow\Console\FlowMakeCommand;
use Laravel\Flow\Console\FlowQueueCommand;

class FlowServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;
    protected $flow;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->app = $app;

        $this->flow = $this->app->make(FlowEvents::class);
    }

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

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function registerEventListeners()
    {
        if ($this->flow->isCached()) {
            $this->flow->loadFromCache();
        } else {
            $this->flow->compile();
        }

        $this->flow->listen();
    }

    protected function registerScheduledJob()
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
