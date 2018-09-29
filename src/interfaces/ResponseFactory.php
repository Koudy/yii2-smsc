<?php

namespace koudy\yii2\smsc\interfaces;

interface ResponseFactory
{
	public function create($parsedResponse): Response;
}
