<?php

use koudy\yii2\smsc\Response;
use koudy\yii2\smsc\ResponseFactory;

class ResponseFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $parsedParams = '::parsedParams::';

        Yii::$container->set(Response::class, ResponseForResponseFactoryTest::class);

        $factory = new ResponseFactory();
        $response = $factory->create($parsedParams);

        Yii::$container->clear(Response::class);

        $this->assertInstanceOf(Response::class, $response);

        $this->assertEquals($parsedParams, $response->getParsedParams());
    }

    public function testCreateWhenUnknownParams()
    {
        $parsedParams = [
            '::some_key::' => '::some param::'
        ];

        $factory = new ResponseFactory();
        $response = $factory->create($parsedParams);

        $this->assertInstanceOf(Response::class, $response);
    }
}

class ResponseForResponseFactoryTest extends Response
{
    private $parsedParams;

    public function setAttributes($values, $safeOnly = true)
    {
        $this->parsedParams = $values;
    }

    public function getParsedParams()
    {
        return $this->parsedParams;
    }
}