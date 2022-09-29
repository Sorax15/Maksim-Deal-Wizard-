<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use App\Models\Deal;
use App\Models\Tracking;
use App\Models\Track;

use Illuminate\Support\Facades\Redis;


class EventsTracking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->event;
        if(is_array($data) && !empty($data)){

            $track = Track::where('deal_id', $data['deal_id'])->first();
            if(!$track)return;

            $tracking = Tracking::create([
                'deal_id' => $data['deal_id'],
                'd_id' => $data['d_id'],
                's_id' => $data['s_id'],
                'page' => $data['page'],
                'env'  => $data['env'],
                'type' => $data['type'],
                'info' => $data['info'],
                'created_at' => $data['created']
            ]);

            $tracking->save();

        }

    }
}
