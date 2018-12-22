<?php

use koudy\yii2\smsc\SendRequest;

class SendRequestTest extends \PHPUnit\Framework\TestCase
{
    public function testGetAuthorizationData()
    {
        $phone = '::phone::';
        $text = '::text::';

        $login = '::login::';
        $password = '::password::';

        $request = new SendRequest($phone, $text, $login, $password);

        $authorizationData = [
            'login' => $login,
            'psw' => $password
        ];

        $this->assertEquals($authorizationData, $request->getAuthorizationData());
    }

    public function testGetRequestParams()
    {
        $phone = '::phone::';
        $text = '::text::';

        $login = '::login::';
        $password = '::password::';

        $responseJsonFormat = 3;
        $op = 1;
        $charset = 'utf-8';

        $request = new SendRequest($phone, $text, $login, $password);

        $requestParams = [
            'phones' => $phone,
            'mes' => $text,
            'fmt' => $responseJsonFormat,
            'op' => $op,
            'charset' => $charset
        ];

        $this->assertEquals($requestParams, $request->getRequestParams());
    }

    public function testGetMethod()
    {
        $request = $this
            ->getMockBuilder(SendRequest::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['getMethod'])
            ->getMock();

        $this->assertEquals('send.php', $request->getMethod());
    }
}
