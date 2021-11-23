<?php

namespace MobilniPlatbyCz\Request;

use MobilniPlatbyCz\Response\SmsResponseInterface;

interface SmsRequestInterface
{
    public function execute(): SmsResponseInterface;
}
