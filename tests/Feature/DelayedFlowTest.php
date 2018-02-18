<?php

namespace Laravel\Flow\Tests\Feature;

use Laravel\Flow\BaseFlow;
use Laravel\Flow\CustomWatcher;
use Laravel\Flow\FlowEvents;
use Laravel\Flow\Tests\IntegrationTest;
use Laravel\Flow\Watcher;
use Carbon\Carbon;

class DelayedFlowTest extends IntegrationTest
{
    public function test_creating_a_flow_with_a_delay_time()
    {
        $flow = new class extends BaseFlow {
            public function watches(): Watcher
            {
                return new CustomWatcher('test.event');
            }

            public function delay() : ?Carbon
            {
                return Carbon::now()->addMinutes(5);
            }

            public function handle($record)
            {
                //
            }
        };

        $flow_events = app()->make(FlowEvents::class);

        $flow_events->register();


    }
}