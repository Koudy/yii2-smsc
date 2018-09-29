<?php

namespace koudy\yii2\smsc\interfaces;

interface Parser
{
	public function parse(string $rawData): array;
}
