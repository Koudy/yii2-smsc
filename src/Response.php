<?php

namespace koudy\yii2\smsc;

use yii\base\Model;

class Response extends Model
{
	/**
	 * @var string
	 */
	public $id;

	/**
	 * @var int
	 */
	public $cnt;

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
	public function getCnt(): ?int
	{
		return $this->cnt;
	}
}
