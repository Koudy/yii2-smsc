<?php

namespace koudy\yii2\smsc\interfaces;

interface Client
{
	/**
	 * @param Request $request
	 * @return Response
	 */
	public function sendRequest(Request $request): Response;
}
