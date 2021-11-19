<?php

namespace MobilniPlatbyCz;

use DateTime;
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
        $timestamp = strtotime($query['timestamp']);
        $smsRequest->setDateTime((new DateTime())->setTimestamp($timestamp));
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
}