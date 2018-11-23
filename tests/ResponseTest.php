<?php

use koudy\yii2\smsc\Response;
use yii\base\Model;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
	public function testInheritance()
	{
		$sender = new Response();
		$this->assertInstanceOf(Model::class, $sender);
	}

	public function testCreate()
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

		$response = new Response($parsedResponse);

		$this->assertEquals($id, $response->getId());
		$this->assertEquals($count, $response->getCnt());
		$this->assertEquals($status, $response->getStatus());
	}

	private function getFaker()
	{
		return Faker\Factory::create();
	}
}
