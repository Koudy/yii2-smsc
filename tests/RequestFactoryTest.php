<?php

use koudy\yii2\smsc\Request;
use koudy\yii2\smsc\RequestFactory;

class RequestFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $phone = '::phones::';
        $text = '::text::';

        $login = '::login::';
        $password = '::password::';

        $responseJsonFormat = 3;
        $op = 1;
        $charset = 'utf-8';

        $factory = new RequestFactory();
        $request = $factory->create($phone, $text, $login, $password);

        $this->assertInstanceOf(Request::class, $request);

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
