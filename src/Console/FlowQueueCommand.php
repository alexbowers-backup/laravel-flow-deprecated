<?php

namespace Laravel\Flow\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Flow\Flow;
use Laravel\Flow\Jobs\PerformFlow;

class FlowQueueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flow:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue up any flows that are now available.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $flows = Flow::where('available_at', '<=', Carbon::now())
            ->whereNull('completed_at')->get();

        $flows->each(function(Flow $flow) {
            $flow->completed_at = Carbon::now();
            $flow->save();

            dispatch(
                new PerformFlow(
                    $flow->flow,
                    $flow->record::find($flow->record_id)
                )
            )->onQueue(config('flow.queue', 'default'));
        });
    }
}
