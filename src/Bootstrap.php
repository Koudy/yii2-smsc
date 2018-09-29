<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Client as ClientInterface;
use koudy\yii2\smsc\interfaces\Parser as ParserInterface;
use koudy\yii2\smsc\interfaces\RequestFactory as RequestFactoryInterface;
use koudy\yii2\smsc\interfaces\ResponseFactory as ResponseFactoryInterface;
use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
	public function bootstrap($app)
	{
		Yii::$container->set(RequestFactoryInterface::class, RequestFactory::class);
		Yii::$container->set(ClientInterface::class, Client::class);
		Yii::$container->set(ParserInterface::class, Parser::class);
		Yii::$container->set(ResponseFactoryInterface::class, ResponseFactory::class);
	}
}
