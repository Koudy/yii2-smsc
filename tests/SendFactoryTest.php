<?php

use koudy\yii2\smsc\SendRequest;
use koudy\yii2\smsc\SendFactory;
use koudy\yii2\smsc\SendResponse;

class SendFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateRequest()
    {
        $phone = '::phones::';
        $text = '::text::';

        $login = '::login::';
        $password = '::password::';

        $responseJsonFormat = 3;
        $op = 1;
        $charset = 'utf-8';

        $factory = new SendFactory();
        $request = $factory->createRequest($phone, $text, $login, $password);

        $this->assertInstanceOf(SendRequest::class, $request);

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

    public function testCreateResponse()
    {
        $parsedParams = '::parsedParams::';

        $safeOnly = false;
        $response = $this->createMock(SendResponse::class);
        $response->expects(self::once())->method('setAttributes')->with($parsedParams, $safeOnly);

        Yii::$container->set(SendResponse::class, $response);

        $factory = new SendFactory();
        $response = $factory->createResponse($parsedParams);

        Yii::$container->clear(SendResponse::class);

        $this->assertInstanceOf(SendResponse::class, $response);
    }

    public function testCreateWhenUnknownParams()
    {
        $parsedParams = [
            '::some_key::' => '::some param::'
        ];

        $factory = new SendFactory();
        $response = $factory->createResponse($parsedParams);

        $this->assertInstanceOf(SendResponse::class, $response);
    }
}
