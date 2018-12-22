<?php

use koudy\yii2\smsc\GetStatusRequest;

class GetStatusRequestTest extends \PHPUnit\Framework\TestCase
{
    public function testGetAuthorizationData()
    {
        $id = '::id::';
        $phone = '::phone::';

        $login = '::login::';
        $password = '::password::';

        $request = new GetStatusRequest($id, $phone, $login, $password);

        $authorizationData = [
            'login' => $login,
            'psw' => $password
        ];

        $this->assertEquals($authorizationData, $request->getAuthorizationData());
    }

    public function testGetRequestParams()
    {
        $id = '::id::';
        $phone = '::phone::';

        $login = '::login::';
        $password = '::password::';

        $responseJsonFormat = 3;
        $charset = 'utf-8';

        $request = new GetStatusRequest($id, $phone, $login, $password);

        $requestParams = [
            'id' => $id,
            'phone' => $phone,
            'fmt' => $responseJsonFormat,
            'charset' => $charset
        ];

        $this->assertEquals($requestParams, $request->getRequestParams());
    }

    public function testGetMethod()
    {
        $request = $this
            ->getMockBuilder(GetStatusRequest::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['getMethod'])
            ->getMock();

        $this->assertEquals('status.php', $request->getMethod());
    }
}
