<?php

use koudy\yii2\smsc\Message;
use koudy\yii2\smsc\interfaces\Message as MessageInterface;

class MessageTest extends \PHPUnit\Framework\TestCase
{
	public function testInterface()
	{
		$message = new Message();
		$this->assertInstanceOf(MessageInterface::class, $message);
	}

	public function testSetGetPhones()
	{
	    $message = new Message();
	    $phones = ['::phone 1::', '::phone 2::'];

	    $message->setPhones($phones);

	    $this->assertEquals($phones, $message->getPhones());
	}

	public function testSetGetText()
	{
	    $message = new Message();
	    $text = '::some text::';

	    $message->setText($text);

	    $this->assertEquals($text, $message->getText());
	}
}
