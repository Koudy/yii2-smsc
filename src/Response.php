<?php

namespace koudy\yii2\smsc;

use yii\base\Component;

class Response extends Component
{
	/**
	 * @var string
	 */
	public $id;

	/**
	 * @var int
	 */
	public $count;

	/**
	 * @return null|string
	 */
	public function getId(): ?string
	{
		return $this->id;
	}

	/**
	 * @return int|null
	 */
	public function getCount(): ?int
	{
		return $this->count;
	}
}
