<?php

namespace Tnt\Sendcloud\Controller;

use dry\http\Request;
use Oak\Dispatcher\Facade\Dispatcher;
use Tnt\Sendcloud\Events\ParcelChanged;
use Tnt\Sendcloud\Exception\SendcloudWebhookException;

class WebhookController
{
    public static function process(Request $request, string $secretKey)
    {
        if ($secretKey) {
            self::verifyWebhookRequest($request, $secretKey);
        }

        $data = json_decode($request->put, true);

        if (! $data['action']) {
            throw new SendCloudWebhookException('Webhook request does not contain an action and is probably malformed.');
        }

        if ($data['action'] === 'parcel_status_changed') {

            $parcelData = array_merge($data, [$data['timestamp'], $data['action']]);

            Dispatcher::dispatch(ParcelChanged::class, new ParcelChanged($parcelData));
        }
    }

    private static function verifyWebhookRequest(Request $request, string $secretKey)
    {
        $sendcloudSignature = $_SERVER['HTTP_SENDCLOUD_SIGNATURE'] ?? null;

        if (! $sendcloudSignature) {
            throw new SendcloudWebhookException('Webhook request does not specify a signature header.');
        }

        // This is a POST request but content only available as 'file_get_contents( 'php://input' )'
        if (hash_hmac("sha256", (string)$request->put, $secretKey) !== $sendcloudSignature) {
            throw new SendCloudWebhookException('Hashed webhook payload does not match Sendcloud-supplied header.');
        }
    }
}