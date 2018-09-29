<?php

namespace koudy\yii2\smsc\interfaces;

interface Message
{
	/**
	 * @param string|array $phones
	 */
	public function setPhones($phones): void;

	/**
	 * @return string|array
	 */
	public function getPhones();

	/**
	 * @param string $text
	 */
	public function setText(string $text): void;

	/**
	 * @return string
	 */
	public function getText(): string;
}
