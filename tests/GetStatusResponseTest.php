<?php

use koudy\yii2\smsc\GetStatusResponse;
use yii\base\Model;

class GetStatusResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testInheritance()
    {
        $sender = new GetStatusResponse();
        $this->assertInstanceOf(Model::class, $sender);
    }

    public function testGetData()
    {
        $status = '::status::';

        $parsedResponse = [
            'status' => $status,
            'last_date' => '::last_date>::',
            'last_timestamp' => '::last_timestamp::',
            'err' => '::err::'
        ];

        $response = new GetStatusResponse();
        $response->setAttributes($parsedResponse, false);

        $expectedData = [
            'status' => $status
        ];
        $this->assertEquals($expectedData, $response->getData());
    }
}
