<?php

namespace koudy\yii2\smsc\interfaces;

use koudy\yii2\smsc\interfaces\Response;

interface Sender
{
	/**
	 * @param Message $message
	 */
	public function send(Message $message): Response;
}
