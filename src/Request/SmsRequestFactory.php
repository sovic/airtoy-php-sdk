<?php

namespace MobilniPlatbyCz\Request;

use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;

class SmsRequestFactory
{
    public static function createFromQuery(array $query): SmsRequest
    {
        $fields = ['timestamp', 'phone', 'sms', 'shortcode', 'country', 'operator', 'att', 'id'];
        foreach ($query as $key => $val) {
            if (!in_array($key, $fields, true)) {
                throw new InvalidArgumentException('Invalid query parameter [' . $key . ']');
            }
        }
        foreach ($fields as $field) {
            if (empty($query[$field])) {
                throw new InvalidArgumentException('Invalid query parameter value [' . $field . ']');
            }
        }

        $smsRequest = new SmsRequest();
        /** Timestamp ISO 8601 (yyyy-MM-ddTHH:mm:ss) SEČ */
        $date = (new DateTime())->setTimestamp(strtotime($query['timestamp']));
        $smsRequest->setDateTime(DateTimeImmutable::createFromMutable($date));
        $smsRequest->setPhone($query['phone']);
        $smsRequest->setMessage($query['sms']);
        $smsRequest->setShortCode($query['shortcode']);
        /** Country ISO 3166-1 */
        $smsRequest->setCountry($query['country']);
        $smsRequest->setOperator($query['operator']);
        $smsRequest->setAttempt((int) $query['att']);
        $smsRequest->setId((int) $query['id']);

        return $smsRequest;
    }

    public static function createDeliveryFromQuery(array $query): SmsDeliveryRequest
    {
        $fields = ['timestamp', 'request', 'status', 'message', 'att', 'ord', 'cnt', 'id'];
        foreach ($query as $key => $val) {
            if (!in_array($key, $fields, true)) {
                throw new InvalidArgumentException('Invalid query parameter [' . $key . ']');
            }
        }
        // status is required
        if (empty($query['status']) || !is_string($query['status'])) {
            throw new InvalidArgumentException('Invalid query parameter [status]');
        }

        $smsRequest = new SmsDeliveryRequest();
        /** Timestamp ISO 8601 (yyyy-MM-ddTHH:mm:ss) SEČ */
        $date = (new DateTime())->setTimestamp(strtotime($query['timestamp']));
        $smsRequest->setDateTime(DateTimeImmutable::createFromMutable($date));
        $smsRequest->setRequestId((int) $query['request']);
        $smsRequest->setStatus($query['status']);
        $smsRequest->setStatusMessage($query['message'] ?? null);
        if (!empty($query['ord'])) {
            $smsRequest->setPartNumber((int) $query['ord']);
        }
        if (!empty($query['cnt'])) {
            $smsRequest->setPartCount((int) $query['cnt']);
        }
        $smsRequest->setAttempt((int) $query['att']);
        $smsRequest->setId((int) $query['id']);

        return $smsRequest;
    }
}
