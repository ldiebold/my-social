<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

class ProcessDropboxValidationWebhookJob extends SpatieProcessWebhookJob
{
    public function handle()
    {
        // Http::get();
        Log::debug('handles validation');
        Log::debug($this->webhookCall->url);
    }
}
