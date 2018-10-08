<?php

use koudy\yii2\smsc\Message;
use yii\base\Component;

class MessageTest extends \PHPUnit\Framework\TestCase
{
	public function testInheritance()
	{
		$sender = new Message();
		$this->assertInstanceOf(Component::class, $sender);
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
