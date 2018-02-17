<?php

namespace Laravel\Flow;

class CustomWatcher implements Watcher
{
    protected $event;

    public function __construct(string $event)
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }
}