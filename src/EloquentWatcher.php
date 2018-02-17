<?php

namespace Laravel\Flow;

use Laravel\Flow\Exceptions\InvalidWatcherException;

class EloquentWatcher implements Watcher
{
    protected $supportedEvents = [
        'retrieved',
        'created',
        'updated',
        'saved',
        'deleted',
        'restored',
    ];

    protected $model;
    protected $event;

    public function __construct(string $model, string $event)
    {
        if (!in_array($event, $this->supportedEvents)) {
            throw new InvalidWatcherException("You cannot watch {$model} for {$event}.");
        }

        $this->model = $model;
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getModel()
    {
        return $this->model;
    }
}
