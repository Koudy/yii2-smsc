<?php

namespace koudy\yii2\smsc;

use Yii;
use yii\base\Component;

class Sender extends Component
{
	/**
	 * @var Client
	 */
	public $client;

	/**
	 * @var string
	 */
	public $login;

	/**
	 * @var string
	 */
	public $url;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @param Message $message
	 * @return Response
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\di\NotInstantiableException
	 */
	public function send(Message $message): Response
	{
		$requestConfig = [
			'login' => $this->login,
			'password' => $this->password,
			'phones' => $message->getPhones(),
			'text' =>$message->getText()
		];

		$request = Yii::$container->get(Request::class, $requestConfig);

		return $this->client->sendRequest($this->url, $request);
	}
}
