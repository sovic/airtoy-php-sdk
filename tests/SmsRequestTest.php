<?php declare(strict_types=1);

use MobilniPlatbyCz\Request\SmsRequestFactory;
use MobilniPlatbyCz\Response\SmsDeliveryResponse;
use MobilniPlatbyCz\Response\SmsResponse;
use PHPUnit\Framework\TestCase;

final class SmsRequestTest extends TestCase
{
    public function testSmsResponse(): void
    {
        $query = [
            'timestamp' => '2021-11-19T11:10:15',
            'phone' => '+420123456789',
            'sms' => 'Sms message text',
            'shortcode' => '90333',
            'country' => 'CZ',
            'operator' => 'TMOBILE',
            'att' => 1,
            'id' => rand(500, 1000),
        ];
        $request = SmsRequestFactory::createFromQuery($query);
        $response = $request->execute();

        $this->assertInstanceOf(SmsResponse::class, $response);

        ob_start();
        $response->setShortCodes(['90333' => '90333099']);
        $response->send('Message');
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('Message;90333099', $content);
    }

    public function testSmsDeliveryResponse(): void
    {
        $query = [
            'timestamp' => '2021-11-19T11:10:15',
            'request' => 123,
            'status' => 'UNDELIVERED',
            'message' => 'CUSTOMER_BLOCKED',
            'ord' => null,
            'cnt' => null,
            'att' => 1,
            'id' => rand(500, 1000),
        ];
        $request = SmsRequestFactory::createDeliveryFromQuery($query);
        $response = $request->execute();

        $this->assertInstanceOf(SmsDeliveryResponse::class, $response);

        ob_start();
        $response->sendEmpty();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertEmpty($content);
    }
}
