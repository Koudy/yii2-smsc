<?php

use koudy\yii2\smsc\GetStatusRequest;
use koudy\yii2\smsc\SendRequest;

class GetStatusRequestTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $id = '::id::';
        $phone = '::phone::';

        $login = '::login::';
        $password = '::password::';

        $responseJsonFormat = 3;
        $charset = 'utf-8';

        $request = new GetStatusRequest($id, $phone, $login, $password);

        $requestParams = [
            'login' => $login,
            'psw' => $password,
            'id' => $id,
            'phones' => $phone,
            'fmt' => $responseJsonFormat,
            'charset' => $charset
        ];

        $this->assertEquals($requestParams, $request->getRequestParams());
    }
}
