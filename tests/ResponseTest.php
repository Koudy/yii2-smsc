<?php

use koudy\yii2\smsc\Response;
use koudy\yii2\smsc\interfaces\Response as ResponseInterface;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
	public function testInterface()
	{
		$response = new Response();
		$this->assertInstanceOf(ResponseInterface::class, $response);
	}

	public function testCreate()
	{
		$id = '::id::';
		$count = $this->getFaker()->numberBetween(1, 1000);

		$parsedResponse = [
			'id' => $id,
			'count' => $count
		];

		$response = new Response($parsedResponse);

		$this->assertEquals($id, $response->getId());
		$this->assertEquals($count, $response->getCount());
	}

	private function getFaker()
	{
		return Faker\Factory::create();
	}
}
