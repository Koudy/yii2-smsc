<?php

use koudy\yii2\smsc\Sender;
use koudy\yii2\smsc\interfaces\Client;
use koudy\yii2\smsc\interfaces\Message;
use koudy\yii2\smsc\interfaces\Parser;
use koudy\yii2\smsc\interfaces\Request;
use koudy\yii2\smsc\interfaces\RequestFactory;
use koudy\yii2\smsc\interfaces\ResponseFactory;
use koudy\yii2\smsc\interfaces\Response;
use koudy\yii2\smsc\interfaces\Sender as SenderInterface;
use yii\base\Component;

class SenderTest extends \PHPUnit\Framework\TestCase
{
	public function testCreateWithYiiCreateObject()
	{
		Yii::$container->set(RequestFactory::class, \koudy\yii2\smsc\RequestFactory::class);
		Yii::$container->set(Client::class, \koudy\yii2\smsc\Client::class);
		Yii::$container->set(Parser::class, \koudy\yii2\smsc\Parser::class);
		Yii::$container->set(ResponseFactory::class, \koudy\yii2\smsc\ResponseFactory::class);

	    $sender = Yii::createObject(Sender::class);
	    $this->assertInstanceOf(SenderInterface::class, $sender);
	}

	public function testInterface()
	{
	    $sender = new Sender(
		    $this->createMock(RequestFactory::class),
		    $this->createMock(Client::class)
	    );
	    $this->assertInstanceOf(SenderInterface::class, $sender);
	}

    public function testInheritance()
    {
    	$sender = new Sender(
		    $this->createMock(RequestFactory::class),
		    $this->createMock(Client::class)
	    );
        $this->assertInstanceOf(Component::class, $sender);
    }

	public function testSend()
	{
		$phones = ['::phones::'];
		$text = '::text::';

		$login = '::login::';
		$password = '::password::';

		$message = $this->createMock(Message::class);
		$message->method('getPhones')->willReturn($phones);
		$message->method('getText')->willReturn($text);
		$request = $this->createMock(Request::class);

		$requestFactory = $this->createMock(RequestFactory::class);
		$requestFactory
			->method('create')
			->with($phones, $text, $login, $password)
			->willReturn($request);

		$response = $this->createMock(Response::class);

		$client = $this->createMock(Client::class);
		$client->method('sendRequest')->with($request)->willReturn($response);

		$config = [
			'login' => $login,
			'password' => $password
		];

		$sender = new Sender($requestFactory, $client, $config);

		$this->assertSame($response, $sender->send($message));
	}

	private function getFaker()
	{
		return Faker\Factory::create();
	}
}
