<?php

namespace MobilniPlatbyCz;

use DateTime;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class SmsRequest
{
    private const AVAILABLE_OPERATORS = ['TMOBILE', 'O2', 'VODAFONE', 'ORANGE', 'CTYRKA'];
    private const AVAILABLE_COUNTRIES = ['CZ', 'PL', 'SK'];

    private DateTime $dateTime;
    private string $phone;
    private string $message;
    private string $shortCode;
    private string $country;
    private string $operator;
    private int $attempt;
    private int $id;

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    /**
     * Phone number or hash, including country calling codes
     * 420 – CZ
     * 421 – SK
     * 48 – PL
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getShortCode(): string
    {
        return $this->shortCode;
    }

    public function setShortCode(string $shortCode): void
    {
        $this->shortCode = $shortCode;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        if (!in_array($country, self::AVAILABLE_COUNTRIES, true)) {
            throw new InvalidArgumentException('Invalid country');
        }

        $this->country = $country;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function setOperator(string $operator): void
    {
        if (!in_array($operator, self::AVAILABLE_OPERATORS, true)) {
            throw new InvalidArgumentException('Invalid operator');
        }

        $this->operator = $operator;
    }

    public function getAttempt(): int
    {
        return $this->attempt;
    }

    public function setAttempt(int $attempt): void
    {
        $this->attempt = $attempt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function sendMoResponse(?string $message = null)
    {
        if (null === $message) {
            $message = '';
        }
        $this->sendSuccessResponse($message);
    }

    public function sendSuccessResponse(string $message)
    {
        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Length' => strlen($message),
        ];
        $response = new Response($message, Response::HTTP_OK, $headers);

        $response->send();
    }
}
