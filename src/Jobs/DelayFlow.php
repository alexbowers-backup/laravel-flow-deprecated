<?php

namespace Laravel\Flow\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Laravel\Flow\Flow;

class DelayFlow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $flow;
    protected $record;
    protected $flow_delay;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($flow, $record, $flow_delay = null)
    {
        $this->flow = $flow;
        $this->record = $record;
        $this->flow_delay = $flow_delay;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Flow::create([
            'flow' => $this->flow,
            'record' => get_class($this->record),
            'record_id' => $this->record->id,
            'available_at' => $this->flow_delay,
        ]);
    }
}
