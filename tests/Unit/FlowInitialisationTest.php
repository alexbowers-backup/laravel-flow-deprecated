<?php

namespace Laravel\Flow\Tests\Unit;

use Illuminate\Contracts\Foundation\Application;
use Laravel\Flow\FlowEvents;
use Laravel\Flow\FlowServiceProvider;
use Laravel\Flow\Tests\TestCase;

class FlowInitialisationTest extends TestCase
{
    protected $mock_application;

    /**
     * @var FlowServiceProvider
     */
    protected $service_provider;

    protected function setUp()
    {
        $this->mock_application = \Mockery::mock(Application::class);

        $this->service_provider = new FlowServiceProvider($this->mock_application);

        parent::setUp();
    }

    /**
     * @test
     */
     function it_can_be_constructed()
     {
        $this->assertInstanceOf(FlowServiceProvider::class, $this->service_provider);
     }

    /**
     * @test
     */
     function i_can_see_that_flow_events_is_loaded()
     {
         throw new \Exception;
         $this->mock_application->shouldReceive('runningInConsole')->andReturn(false);
         $this->mock_application->shouldReceive('afterResolving');
         $mock_flow_events = \Mockery::mock(FlowEvents::class);

         $mock_flow_events->shouldReceive('isCached')->andReturn(false);

         $this->service_provider->boot();
     }
}