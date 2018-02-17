<?php

namespace Laravel\Flow;

use Carbon\Carbon;

abstract class BaseFlow
{
    abstract public function watches() : Watcher;

    public function delay() : ?Carbon
    {
        return null;
    }

    abstract public function handle($record);
}