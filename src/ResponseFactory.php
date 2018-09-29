<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Response as ResponseInterface;
use Yii;

class ResponseFactory implements interfaces\ResponseFactory
{
	/**
	 * @inheritdoc
	 */
	public function create($parsedResponse): ResponseInterface
	{
		return Yii::createObject(ResponseInterface::class, [$parsedResponse]);
	}
}
