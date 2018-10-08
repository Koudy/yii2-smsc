<?php

use koudy\yii2\smsc\Request;
use yii\base\Component;

class RequestTest extends \PHPUnit\Framework\TestCase
{
	public function testInheritance()
	{
		$sender = new Request();
		$this->assertInstanceOf(Component::class, $sender);
	}

	public function testGetRequestParamsWhenOnlyOnePhone()
	{
		$login = '::login::';
		$password = '::password::';

		$phones = '::phones::';
		$text = '::text::';

		$responseJsonFormat = 3;

		$config = [
			'login' => $login,
			'password' => $password,
			'text' => $text,
			'phones' => $phones
		];

		$request = new Request($config);

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

		$config = [
			'login' => $login,
			'password' => $password,
			'text' => $text,
			'phones' => $phones
		];

		$request = new Request($config);

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
