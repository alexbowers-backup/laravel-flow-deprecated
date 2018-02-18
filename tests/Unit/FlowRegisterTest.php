<?php

namespace Laravel\Flow\Tests\Unit;

use Laravel\Flow\BaseFlow;
use Laravel\Flow\EloquentWatcher;
use Laravel\Flow\Tests\TestCase;
use Laravel\Flow\Tests\Unit\Fixtures\FakeModel;
use Laravel\Flow\CustomWatcher;
use Laravel\Flow\FlowEvents;
use Laravel\Flow\Tests\Unit\Fixtures\FakeModel2;
use Laravel\Flow\Watcher;

class FlowRegisterTest extends TestCase
{
    /**
     * @test
     */
    public function i_can_register_a_custom_watcher_flow()
    {
        $flow = new class extends BaseFlow {
            public function watches(): Watcher
            {
                return new CustomWatcher('test.event');
            }

            public function handle($record)
            {
                //
            }
        };

        $flow_events = $this->app->make(FlowEvents::class);

        $flow_events->register($flow);

        $this->assertArrayHasKey('test.event', $flow_events->getEvents('custom'));
        $this->assertCount(1, $flow_events->getEvents('custom')['test.event']);
    }

    /**
     * @test
     */
    public function i_can_register_an_eloquent_watcher_flow()
    {
        $flow = new class extends BaseFlow {
            public function watches(): Watcher
            {
                return new EloquentWatcher(FakeModel::class, 'created');
            }

            public function handle($record)
            {
                //
            }
        };

        $flow_events = $this->app->make(FlowEvents::class);

        $flow_events->register($flow);

        $this->assertArrayHasKey('Laravel\Flow\Tests\Unit\Fixtures\FakeModel', $flow_events->getEvents('created'));
        $this->assertCount(1, $flow_events->getEvents('created')['Laravel\Flow\Tests\Unit\Fixtures\FakeModel']);
    }

    /**
     * @test
     */
     function i_can_register_multiple_eloquent_watchers_for_same_event()
     {
         $flow = new class extends BaseFlow {
             public function watches(): Watcher
             {
                 return new EloquentWatcher(FakeModel::class, 'created');
             }

             public function handle($record)
             {
                 //
             }
         };

         $flow2 = new class extends BaseFlow {
             public function watches(): Watcher
             {
                 return new EloquentWatcher(FakeModel2::class, 'created');
             }

             public function handle($record)
             {
                 //
             }
         };

         $flow_events = $this->app->make(FlowEvents::class);

         $flow_events->register($flow);
         $flow_events->register($flow2);

         $this->assertArrayHasKey('Laravel\Flow\Tests\Unit\Fixtures\FakeModel', $flow_events->getEvents('created'));
         $this->assertArrayHasKey('Laravel\Flow\Tests\Unit\Fixtures\FakeModel2', $flow_events->getEvents('created'));

         $this->assertCount(1, $flow_events->getEvents('created')['Laravel\Flow\Tests\Unit\Fixtures\FakeModel']);
         $this->assertCount(1, $flow_events->getEvents('created')['Laravel\Flow\Tests\Unit\Fixtures\FakeModel2']);
     }

    /**
     * @test
     */
    function i_can_register_multiple_eloquent_watchers_for_same_event_and_model()
    {
        $flow = new class extends BaseFlow {
            public function watches(): Watcher
            {
                return new EloquentWatcher(FakeModel::class, 'created');
            }

            public function handle($record)
            {
                //
            }
        };

        $flow2 = new class extends BaseFlow {
            public function watches(): Watcher
            {
                return new EloquentWatcher(FakeModel::class, 'created');
            }

            public function handle($record)
            {
                //
            }
        };

        $flow_events = $this->app->make(FlowEvents::class);

        $flow_events->register($flow);
        $flow_events->register($flow2);

        $this->assertArrayHasKey('Laravel\Flow\Tests\Unit\Fixtures\FakeModel', $flow_events->getEvents('created'));

        $this->assertCount(2, $flow_events->getEvents('created')['Laravel\Flow\Tests\Unit\Fixtures\FakeModel']);
    }

    /**
     * @test
     */
    function i_can_register_multiple_eloquent_watchers_for_same_model_but_different_events()
    {
        $flow = new class extends BaseFlow {
            public function watches(): Watcher
            {
                return new EloquentWatcher(FakeModel::class, 'created');
            }

            public function handle($record)
            {
                //
            }
        };

        $flow2 = new class extends BaseFlow {
            public function watches(): Watcher
            {
                return new EloquentWatcher(FakeModel::class, 'updated');
            }

            public function handle($record)
            {
                //
            }
        };

        $flow_events = $this->app->make(FlowEvents::class);

        $flow_events->register($flow);
        $flow_events->register($flow2);

        $this->assertArrayHasKey('Laravel\Flow\Tests\Unit\Fixtures\FakeModel', $flow_events->getEvents('created'));
        $this->assertArrayHasKey('Laravel\Flow\Tests\Unit\Fixtures\FakeModel', $flow_events->getEvents('updated'));

        $this->assertCount(1, $flow_events->getEvents('created')['Laravel\Flow\Tests\Unit\Fixtures\FakeModel']);
        $this->assertCount(1, $flow_events->getEvents('updated')['Laravel\Flow\Tests\Unit\Fixtures\FakeModel']);
    }
}