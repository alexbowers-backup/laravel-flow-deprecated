<?php

namespace Laravel\Flow;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Laravel\Flow\Exceptions\InvalidFlowRegistrationException;
use Laravel\Flow\Jobs\DelayFlow;

class FlowEvents
{
    protected $events = [
        'retrieved' => [],
        'created' => [],
        'updated' => [],
        'saved' => [],
        'deleted' => [],
        'restored' => [],
        'custom' => [],
    ];

    private $listening = false;

    private $app;
    private $filesystem;

    public function __construct(Application $app, Filesystem $filesystem)
    {
        $this->app = $app;
        $this->filesystem = $filesystem;
    }

    public function register(BaseFlow $flow)
    {
        if (!$this->listening) {
            $watcher = $flow->watches();

            if ($watcher instanceof CustomWatcher) {
                $this->events['custom'][$watcher->getEvent()][] = get_class($flow);
            } else if ($watcher instanceof EloquentWatcher) {
                $this->events[$watcher->getEvent()][$watcher->getModel()][] = get_class($flow);
            } else {
                throw new InvalidFlowRegistrationException("You are trying to use an unsupported Watcher.");
            }

            return $this;
        }

        throw new InvalidFlowRegistrationException("You have tried to register a flow after it has started listening.");
    }

    public function getEvents($key = null)
    {
        return $this->events[$key] ?? $this->events;
    }

    public function compile()
    {
        $files = $this->filesystem->glob($this->app->basePath() . '/app/Flows/*.php');

        collect($files)->map(function($file) {
            return str_replace($this->app->basePath() . '/app', $this->app->getNamespace(), $file);
        })->map(function($file) {
            return str_replace('.php', '', $file);
        })->map(function($file) {
            return str_replace('/', '\\', $file);
        })->map(function($file) {
            return '\\' . str_replace('\\\\', '\\', $file);
        })->map(function($file){
            return $this->app->make($file);
        })->filter(function(BaseFlow $class){
            return $class->watches() instanceof Watcher;
        })->each(function($class) {
            $this->register($class);
        });
    }

    public function cache()
    {
        $this->filesystem->put(
            $this->app->bootstrapPath() . '/cache/flow.php',
            '<?php return '.var_export($this->events, true).';'.PHP_EOL
        );
    }

    public function isCached()
    {
        return $this->filesystem->exists($this->app->bootstrapPath() . '/cache/flow.php');
    }

    public function loadFromCache()
    {
        $this->events = require $this->app->bootstrapPath() . '/cache/flow.php';

        return $this;
    }

    public function listen()
    {
        $this->listening = true;

        foreach ($this->events as $event => $models) {
            if ($event == 'custom') {
                $this->listenForCustomEvent($models);
            } else {
                $this->listenForEloquentEvent($event, $models);
            }
        }
    }

    public function listenForCustomEvent(array $events = [])
    {
        foreach ($events as $event => $flows) {
            Event::listen($event, function ($response) use ($flows) {
                $this->listenToFlows($flows, $response);
            });
        }
    }

    public function listenForEloquentEvent($event, array $models = [])
    {
        foreach ($models as $model => $flows) {
            Event::listen("eloquent.{$event}: {$model}", function ($response) use ($flows) {
                $this->listenToFlows($flows, $response);
            });
        }
    }

    private function listenToFlows($flows, $response)
    {
        foreach ($flows as $flow) {
            /**
             * @var $instance BaseFlow
             */
            $instance = $this->app->make($flow);

            $delay = $instance->delay();

            dispatch(new DelayFlow($flow, $response, $delay))->onQueue(config('flow.queue', 'default'));
        }
    }
}