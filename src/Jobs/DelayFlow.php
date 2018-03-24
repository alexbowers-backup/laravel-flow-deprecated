<?php

namespace Laravel\Flow\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Flow\Delay;
use Laravel\Flow\Flow;

class DelayFlow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $flow;
    protected $record;
    protected $flow_delay;
    protected $flow_interval;
    protected $flow_times;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($flow, $record, $flow_delay = null)
    {
        $this->flow = $flow;
        $this->record = $record;

        if ($flow_delay instanceof Delay) {
            $this->flow_interval = $flow_delay->getInterval();
            $this->flow_times = $flow_delay->getTimes();
            $this->flow_delay = $flow_delay->getStart();
        } else {
            $this->flow_delay = $flow_delay;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        dd($this);
        Flow::create([
            'flow' => $this->flow,
            'record' => get_class($this->record),
            'record_id' => $this->record->id,
            'interval' => $this->flow_interval,
            'times' => $this->flow_times,
            'available_at' => $this->flow_delay,
        ]);
    }
}
