<?php

use koudy\yii2\smsc\Sender;
use koudy\yii2\smsc\Client;
use koudy\yii2\smsc\Message;
use koudy\yii2\smsc\Parser;
use koudy\yii2\smsc\Request;
use koudy\yii2\smsc\Response;
use yii\base\Component;

class SenderTest extends \PHPUnit\Framework\TestCase
{
    public function testInheritance()
    {
    	$sender = new Sender();
        $this->assertInstanceOf(Component::class, $sender);
    }

	public function testSend()
	{
		$phones = ['::phones::'];
		$text = '::text::';

		$login = '::login::';
		$password = '::password::';

		$url = '::url::';

		$message = $this->createMock(Message::class);
		$message->method('getPhones')->willReturn($phones);
		$message->method('getText')->willReturn($text);

		$request = $this->createMock(Request::class);
		$response = $this->createMock(Response::class);

		$client = $this->createMock(Client::class);
		$client->method('sendRequest')->with($url, $request)->willReturn($response);

		$requestConfig = [
			'login' => $login,
			'password' => $password,
			'text' => $text,
			'phones' => $phones
		];

		$container = $this->createMock(yii\di\Container::class);
		$container
			->expects(self::once())
			->method('get')
			->with(Request::class, $requestConfig)
			->willReturn($request);

		Yii::$container = $container;

		$senderConfig = [
			'login' => $login,
			'password' => $password,
			'url' => $url,
			'client' => $client
		];

		$sender = new Sender($senderConfig);

		$this->assertSame($response, $sender->send($message));
	}

	private function getFaker()
	{
		return Faker\Factory::create();
	}
}
