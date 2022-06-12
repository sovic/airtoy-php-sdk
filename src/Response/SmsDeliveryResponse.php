<?php

namespace MobilniPlatbyCz\Response;

use Symfony\Component\HttpFoundation\Response;

class SmsDeliveryResponse implements SmsResponseInterface
{
    public function sendEmpty(): void
    {
        $response = new Response(null, Response::HTTP_NO_CONTENT);
        $response->send();
    }
}
