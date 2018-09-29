<?php

namespace koudy\yii2\smsc\interfaces;

interface RequestFactory
{
	/**
	 * @param string|array $phones
	 * @param string $text
	 * @param string $login
	 * @param string $password
	 * @return Request
	 */
	public function create($phones, string $text, string $login, string $password): Request;
}
