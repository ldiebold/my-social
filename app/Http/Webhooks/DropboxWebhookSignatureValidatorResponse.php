<?php

namespace App\Http\Webhooks;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookResponse\RespondsToWebhook;
use Symfony\Component\HttpFoundation\Response;

class DropboxWebhookSignatureValidatorResponse implements RespondsToWebhook
{
    public function respondToValidWebhook(Request $request, WebhookConfig $config): Response
    {
        $response = response($request->challenge);
        $response->withHeaders([
          'Content-Type' => 'text/plain',
          'X-Content-Type-Options' => 'nosniff'
        ]);

        return $response;
    }
}
