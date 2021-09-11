<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob as SpatieProcessWebhookJob;

class ProcessDropboxWebhookJob extends SpatieProcessWebhookJob
{
    public function handle()
    {
        // Http::get();
        Log::debug('handles dropbox webhook! WooHoo!!!');
        Log::debug($this->webhookCall->toJson());
    }
}
