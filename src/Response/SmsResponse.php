<?php

namespace MobilniPlatbyCz\Response;

use MobilniPlatbyCz\Request\SmsRequest;
use Symfony\Component\HttpFoundation\Response;

class SmsResponse implements SmsResponseInterface
{
    private SmsRequest $request;

    public function __construct(SmsRequest $request)
    {
        $this->request = $request;
    }

    public function send(string $message, int $httpCode = Response::HTTP_OK): void
    {
        switch ($this->request->getCountry()) {
            case 'CZ':
                if (in_array($this->request->getShortCode(), [90333, 90944, 90210, 90733])) {
                    $message .= ';' . $this->request->getShortCode();
                }
                break;
            case 'SK':
                if (in_array($this->request->getShortCode(), [6675, 6663, 6667, 6676, 6674])) {
                    $message .= ';' . $this->request->getShortCode();
                } elseif (in_array($this->request->getShortCode(), [90333, 90944, 90210, 90733])) {
                    $message .= ';' . $this->request->getShortCode() . 'priceEUR*100';
                }
                break;
            default:
                $message .= ';' . $this->request->getShortCode();
                break;
        }

        $headers = [
            'Content-Length' => strlen($message),
            'Content-Type' => 'text/plain',
        ];

        $response = new Response($message, $httpCode, $headers);
        $response->send();
    }
}
