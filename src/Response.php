<?php

namespace koudy\yii2\smsc;

use yii\base\Component;

class Response extends Component implements interfaces\Response
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
	 * @inheritdoc
	 */
	public function getId(): ?string
	{
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getCount(): ?int
	{
		return $this->count;
	}
}
