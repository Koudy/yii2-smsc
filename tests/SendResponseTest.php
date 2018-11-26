<?php

use koudy\yii2\smsc\SendResponse;
use yii\base\Model;

class SendResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testInheritance()
    {
        $sender = new SendResponse();
        $this->assertInstanceOf(Model::class, $sender);
    }

    public function testGetDataWithoutStatus()
    {
        $id = '::id::';
        $count = $this->getFaker()->numberBetween(1, 1000);
        $status = '::status::';

        $parsedResponse = [
            'id' => $id,
            'cnt' => $count,
            'phones' => [
                [
                    'phone' => '::phone::',
                    'mccmnc' => '::mccmnc::',
                    'cost' => '::cost::'
                ],
            ]
        ];

        $response = new SendResponse($parsedResponse);

        $expectedData = [
            'id' => $id,
            'count' => $count
        ];
        $this->assertEquals($expectedData, $response->getData());
    }

    public function testGetDataWithStatus()
    {
        $id = '::id::';
        $count = $this->getFaker()->numberBetween(1, 1000);
        $status = '::status::';

        $parsedResponse = [
            'id' => $id,
            'cnt' => $count,
            'phones' => [
                [
                    'phone' => '::phone::',
                    'mccmnc' => '::mccmnc::',
                    'cost' => '::cost::',
                    'status' => $status,
                    'error' => '::error::'
                ],
            ]
        ];

        $response = new SendResponse($parsedResponse);

        $expectedData = [
            'id' => $id,
            'count' => $count,
            'status' => $status
        ];
        $this->assertEquals($expectedData, $response->getData());
    }

    private function getFaker()
    {
        return Faker\Factory::create();
    }
}
