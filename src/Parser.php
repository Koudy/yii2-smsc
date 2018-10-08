<?php

namespace koudy\yii2\smsc;

use yii\base\Component;

class Parser extends Component
{
	/**
	 * @param string $rawData
	 * @return array
	 */
	public function parse(string $rawData): array
	{
		return json_decode($rawData, true);
	}
}
