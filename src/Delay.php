<?php

namespace Laravel\Flow;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Facade;

class Delay extends Facade
{
    private $start;
    private $interval;
    private $times = 1;

    public function start(Carbon $datetime)
    {
        $this->start = $datetime;

        return $this;
    }

    public function interval(CarbonInterval $interval)
    {
        $this->interval = $interval;

        return $this;
    }

    public function times($times = 1)
    {
        $this->times = $times;

        return $this;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getInterval()
    {
        return $this->interval;
    }

    public function getTimes()
    {
        return $this->times;
    }
}