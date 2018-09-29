<?php

namespace koudy\yii2\smsc\interfaces;

interface Response
{
	/**
	 * @return null|string
	 */
	public function getId(): ?string;

	/**
	 * @return int|null
	 */
	public function getCount(): ?int;
}
