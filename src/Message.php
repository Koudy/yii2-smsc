<?php

namespace koudy\yii2\smsc;

class Message implements interfaces\Message
{
	/**
	 * @var string|array
	 */
	private $phones;

	/**
	 * @var string
	 */
	private $text;

	/**
	 * @inheritdoc
	 */
	public function setPhones($phones): void
	{
		$this->phones = $phones;
	}

	/**
	 * @inheritdoc
	 */
	public function getPhones()
	{
		return $this->phones;
	}

	/**
	 * @inheritdoc
	 */
	public function setText(string $text): void
	{
		$this->text = $text;
	}

	/**
	 * @inheritdoc
	 */
	public function getText(): string
	{
		return $this->text;
	}
}
