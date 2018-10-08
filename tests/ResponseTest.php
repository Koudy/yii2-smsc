<?php

use koudy\yii2\smsc\Response;
use yii\base\Component;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
	public function testInheritance()
	{
		$sender = new Response();
		$this->assertInstanceOf(Component::class, $sender);
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
