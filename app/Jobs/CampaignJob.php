<?php

namespace App\Jobs;

use App\Mail\SendMailable;
use App\Models\Campaign;
use App\Models\Customer;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class CampaignJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign, $customer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign, $customer)
    {
        $this->campaign = $campaign;
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->campaign->template->type == "email") {
            //replace this function with common mail send function
            Mail::to($this->customer->email)->send(new SendMailable($this->campaign->template->content));
        } elseif ($this->campaign->template->type == "sms") {
            //function to trigger sms here
        } elseif ($this->campaign->template->type == "push") {
            //function to trigger pusher here
        }
    }
}
