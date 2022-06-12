# www.mobilni-platby.cz PHP SDK

## Requirements

- PHP >= 8.0

## Installation

Using [Composer](https://getcomposer.org/doc/00-intro.md)

```bash
composer require sovic/mobilni-platby-cz-php-sdk
```

## Example usage

```php

$query = […]; // query from MobilniPlatby server
$payment = …; // local payment object 

try {
    $smsRequest = SmsRequestFactory::createFromQuery($query);
    $response = $smsRequest->execute();
    // provide shortcodes used for responses 
    $response->setShortCodes([
        '90733' => '90733099',
    ]);

    // store providerId to local payment object
    $payment->setProviderId($smsRequest->getId());

    $response->send('Your service has been activated, thank you');
} catch (Exception $e) {
    $logger->error('Unable to process SMS message [' . $e->getMessage() . ']');
    # TODO handle error
}
```

## Tests

```bash
./vendor/bin/phpunit tests
```
