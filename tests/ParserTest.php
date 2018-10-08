<?php

use koudy\yii2\smsc\Parser;
use yii\base\Component;

class ParserTest extends \PHPUnit\Framework\TestCase
{
	public function testInheritance()
	{
		$sender = new Parser();
		$this->assertInstanceOf(Component::class, $sender);
	}

	public function testParse()
	{
		$rawData = '{"param 1":"value 1","param 2":{"some key 1":"value 2","some key 2":"value 3"}}';

		$parser = new Parser();
		$parsedDate = $parser->parse($rawData);

		$requestParams = [
			'param 1' => 'value 1',
			'param 2' => [
				'some key 1' => 'value 2',
				'some key 2' => 'value 3'
			],
		];

		$this->assertEquals($requestParams, $parsedDate);
	}
}
