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

        $this->assertInstanceOf(Response::class, $response);

        $this->assertEquals($parsedParams, $response->getParsedParams());
    }
}

class ResponseForResponseFactoryTest extends Response
{
    private $parsedParams;

    public function __construct($parsedParams)
    {
        $this->parsedParams = $parsedParams;
    }

    public function getParsedParams()
    {
        return $this->parsedParams;
    }
}