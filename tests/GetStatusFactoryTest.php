<?php

use koudy\yii2\smsc\GetStatusRequest;
use koudy\yii2\smsc\GetStatusFactory;
use koudy\yii2\smsc\GetStatusResponse;

class GetStatusFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateRequest()
    {
        $id = '::id::';
        $phone = '::phone::';

        $login = '::login::';
        $password = '::password::';

        $responseJsonFormat = 3;
        $charset = 'utf-8';

        $factory = new GetStatusFactory();
        $request = $factory->createRequest($id, $phone, $login, $password);

        $this->assertInstanceOf(GetStatusRequest::class, $request);

        $requestParams = [
            'login' => $login,
            'psw' => $password,
            'id' => $id,
            'phone' => $phone,
            'fmt' => $responseJsonFormat,
            'charset' => $charset
        ];

        $this->assertEquals($requestParams, $request->getRequestParams());
    }

    public function testCreateResponse()
    {
        $parsedParams = '::parsedParams::';

        $safeOnly = false;
        $response = $this->createMock(GetStatusResponse::class);
        $response->expects(self::once())->method('setAttributes')->with($parsedParams, $safeOnly);

        Yii::$container->set(GetStatusResponse::class, $response);

        $factory = new GetStatusFactory();
        $response = $factory->createResponse($parsedParams);

        Yii::$container->clear(GetStatusResponse::class);

        $this->assertInstanceOf(GetStatusResponse::class, $response);
    }

    public function testCreateWhenUnknownParams()
    {
        $parsedParams = [
            '::some_key::' => '::some param::'
        ];

        $factory = new GetStatusFactory();
        $response = $factory->createResponse($parsedParams);

        $this->assertInstanceOf(GetStatusResponse::class, $response);
    }
}
