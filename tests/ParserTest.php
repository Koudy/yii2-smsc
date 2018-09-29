<?php

use koudy\yii2\smsc\Parser;
use koudy\yii2\smsc\interfaces\Parser as ParserInterface;

class ParserTest extends \PHPUnit\Framework\TestCase
{
	public function testInterface()
	{
	    $parser = new Parser();
	    $this->assertInstanceOf(ParserInterface::class, $parser);
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
