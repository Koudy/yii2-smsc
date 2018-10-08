<?php

namespace koudy\yii2\smsc;

use yii\base\Component;

class Message extends Component
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
	 * @param $phones
	 */
	public function setPhones($phones): void
	{
		$this->phones = $phones;
	}

	/**
	 * @return array|string
	 */
	public function getPhones()
	{
		return $this->phones;
	}

	/**
	 * @param string $text
	 */
	public function setText(string $text): void
	{
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getText(): string
	{
		return $this->text;
	}
}
