<?php

use koudy\yii2\smsc\Request;
use koudy\yii2\smsc\interfaces\Request as RequestInterface;

class RequestTest extends \PHPUnit\Framework\TestCase
{
	public function testInterface()
	{
		$request = $this
			->getMockBuilder(Request::class)
			->disableOriginalConstructor()
			->getMock();
		$this->assertInstanceOf(RequestInterface::class, $request);
	}

	public function testGetRequestParamsWhenOnlyOnePhone()
	{
		$phones = '::phones::';
		$text = '::text::';

		$login = '::login::';
		$password = '::password::';

		$responseJsonFormat = 3;

		$request = new Request($phones, $text, $login, $password);

		$requestParams = [
			'login' => $login,
			'psw' => $password,
			'phones' => $phones,
			'mes' => $text,
			'fmt' => $responseJsonFormat
		];

		$this->assertEquals($requestParams, $request->getRequestParams());
	}

	public function testGetRequestParamsWhenManyPhones()
	{
		$phones = ['::phone 1::', '::phone 2::'];
		$text = '::text::';

		$login = '::login::';
		$password = '::password::';

		$responseJsonFormat = 3;

		$request = new Request($phones, $text, $login, $password);

		$requestParams = [
			'login' => $login,
			'psw' => $password,
			'phones' => '::phone 1::,::phone 2::',
			'mes' => $text,
			'fmt' => $responseJsonFormat
		];

		$this->assertEquals($requestParams, $request->getRequestParams());
	}
}
