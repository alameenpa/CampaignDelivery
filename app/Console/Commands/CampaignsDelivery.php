<?php

namespace App\Console\Commands;

use App\Jobs\CampaignJob;
use App\Models\Campaign;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\LazyCollection;

class CampaignsDelivery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:campaigns-delivery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'campaigns delivery';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //fetch campaigns
        // $campaigns = Campaign::where('scheduled_at', now())->where('status', 0)->with('template')->get();
        $campaigns = Campaign::where('status', 0)->with('template')->get();

        if (!empty($campaigns)) {
            //fetch chunk value for iteration
            $chunkValue = 1;
            $customersCount = Customer::count();
            if ($customersCount > 100) {
                $chunkValue = floor($customersCount / 100);
            }

            //init batch
            $batch = Bus::batch([])->dispatch();

            //iterate through campaingns
            foreach ($campaigns as $campaign) {
                //apply user segment critaria here to fetch customer list
                Customer::inRandomOrder()->cursor()
                    ->map(fn (Customer $customer) => (new CampaignJob($campaign, $customer)))
                    ->filter()
                    ->chunk($chunkValue)
                    ->each(function (LazyCollection $jobs, $ky) use ($batch, $campaign) {
                        Campaign::where('id', $campaign->id)->update(['delivery_status' => ($ky + 1)]);
                        $batch->add($jobs); // 1000 jobs are now added in one go
                    });
                Campaign::where('id', $this->campaign->id)->update(['status' => true]);
            }
        }

        return 0;
    }
}
