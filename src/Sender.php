<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Client;
use koudy\yii2\smsc\interfaces\Message;
use koudy\yii2\smsc\interfaces\RequestFactory;
use koudy\yii2\smsc\interfaces\Response;

use yii\base\Component;

class Sender extends Component implements interfaces\Sender
{
	/**
	 * @var RequestFactory
	 */
	private $requestFactory;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var string
	 */
	public $login;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @param RequestFactory $requestFactory
	 * @param Client $client
	 * @param array $config
	 */
	public function __construct(
		RequestFactory $requestFactory,
		Client $client,
		array $config = []
	)
	{
		$this->requestFactory = $requestFactory;
		$this->client = $client;

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function send(Message $message): Response
	{
		$request = $this->requestFactory->create(
			$message->getPhones(),
			$message->getText(),
			$this->login,
			$this->password
		);

		return $this->client->sendRequest($request);
	}
}
