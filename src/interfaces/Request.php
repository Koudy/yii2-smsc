<?php

namespace koudy\yii2\smsc\interfaces;

interface Request
{
	/**
	 * @return array
	 */
	public function getRequestParams(): array;
}
