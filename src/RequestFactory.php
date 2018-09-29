<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Request as RequestInterface;

class RequestFactory implements interfaces\RequestFactory
{
	/**
	 * @inheritdoc
	 */
	public function create($phones, string $text, string $login, string $password): RequestInterface
	{
		return new Request($phones, $text, $login, $password);
	}
}
