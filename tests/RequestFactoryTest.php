<?php

use koudy\yii2\smsc\Request;
use koudy\yii2\smsc\RequestFactory;
use koudy\yii2\smsc\interfaces\RequestFactory as RequestFactoryInterface;

class RequestFactoryTest extends \PHPUnit\Framework\TestCase
{
	public function testInterface()
	{
	    $factory = new RequestFactory();
	    $this->assertInstanceOf(RequestFactoryInterface::class, $factory);
	}

	public function testCreate()
	{
		$phones = '::phones::';
		$text = '::text::';

		$login = '::login::';
		$password = '::password::';

		$responseJsonFormat = 3;

		$factory = new RequestFactory();
		$request = $factory->create($phones, $text, $login, $password);

		$this->assertInstanceOf(Request::class, $request);

		$requestParams = [
			'login' => $login,
			'psw' => $password,
			'phones' => $phones,
			'mes' => $text,
			'fmt' => $responseJsonFormat
		];

		$this->assertEquals($requestParams, $request->getRequestParams());
	}
}
