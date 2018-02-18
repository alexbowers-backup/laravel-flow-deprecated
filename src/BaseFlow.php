<?php

namespace Laravel\Flow;

use Carbon\Carbon;

abstract class BaseFlow
{
    abstract public function watches() : Watcher;

    /**
     *
     *
     * @return Carbon|Delay
     */
    public function delay()
    {
        return null;
    }

    abstract public function handle($record);
}