<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

class ProcessWebhookJob extends SpatieProcessWebhookJob
{
    public function handle()
    {
        Log::debug('handles the job');
        Log::debug($this->webhookCall->toJson());
    }
}
