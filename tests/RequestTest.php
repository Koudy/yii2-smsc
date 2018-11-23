<?php

use koudy\yii2\smsc\Request;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $phone = '::phone::';
        $text = '::text::';

        $login = '::login::';
        $password = '::password::';

        $responseJsonFormat = 3;
        $op = 1;
        $charset = 'utf-8';

        $request = new Request($phone, $text, $login, $password);

        $requestParams = [
            'login' => $login,
            'psw' => $password,
            'phones' => $phone,
            'mes' => $text,
            'fmt' => $responseJsonFormat,
            'op' => $op,
            'charset' => $charset
        ];

        $this->assertEquals($requestParams, $request->getRequestParams());
    }
}
