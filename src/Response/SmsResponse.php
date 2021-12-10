<?php

namespace MobilniPlatbyCz\Response;

use InvalidArgumentException;
use MobilniPlatbyCz\Request\SmsRequest;
use Symfony\Component\HttpFoundation\Response;

class SmsResponse implements SmsResponseInterface
{
    private SmsRequest $request;
    private array $shortCodes;

    public function __construct(SmsRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param array $responseShortCodes
     * @return void
     * @example
     *  [
     *    // phone-number => matched-shortcode-with-price-level (string => string)
     *    '90733' => '90733099',
     *  ]
     */
    public function setShortCodes(array $responseShortCodes): void
    {
        $this->shortCodes = $responseShortCodes;
    }

    private function getResponseShortCode(string $shortCode): string
    {
        if (!isset($this->shortCodes[$shortCode])) {
            throw new InvalidArgumentException('Response shortcode not initialized');
        }

        return $this->shortCodes[$shortCode];
    }

    public function send(string $message, int $httpCode = Response::HTTP_OK): void
    {
        $request = $this->request;
        switch ($request->getCountry()) {
            case 'CZ':
                if (in_array($request->getShortCode(), ['90333', '90944', '90210', '90733'], true)) {
                    $message .= ';' . $this->getResponseShortCode($request->getShortCode());
                }
                break;
            case 'SK':
                if (in_array($request->getShortCode(), ['6675', '6663', '6667', '6676', '6674'], true)) {
                    $message .= ';' . $this->getResponseShortCode($request->getShortCode());
                } elseif ($request->getShortCode() === '8877') {
                    $message .= ';' . $this->getResponseShortCode($request->getShortCode());
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
