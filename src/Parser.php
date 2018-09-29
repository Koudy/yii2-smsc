<?php

namespace koudy\yii2\smsc;

class Parser implements interfaces\Parser
{
	/**
	 * @inheritdoc
	 */
	public function parse(string $rawData): array
	{
		return json_decode($rawData, true);
	}
}
