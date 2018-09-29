<?php

namespace koudy\yii2\smsc;

use GuzzleHttp\Client as GuzzleClient;
use koudy\yii2\smsc\interfaces\Request;
use koudy\yii2\smsc\interfaces\Parser;
use koudy\yii2\smsc\interfaces\Response;
use koudy\yii2\smsc\interfaces\ResponseFactory;

class Client implements interfaces\Client
{
	const URL = 'https://smsc.ru/sys/send.php';
	/**
	 * @var GuzzleClient
	 */
	private $guzzleClient;

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * @var ResponseFactory
	 */
	private $responseFactory;

	/**
	 * @param GuzzleClient $guzzleClient
	 * @param Parser $parser
	 * @param ResponseFactory $responseFactory
	 */
	public function __construct(
		GuzzleClient $guzzleClient,
		Parser $parser,
		ResponseFactory $responseFactory
	)
	{
		$this->guzzleClient = $guzzleClient;
		$this->parser = $parser;
		$this->responseFactory = $responseFactory;
	}

	/**
	 * @inheritdoc
	 */
	public function sendRequest(Request $request): Response
	{
		$guzzleResponse = $this->guzzleClient->request('POST', self::URL, [
			'form_params' => $request->getRequestParams()
		]);

		$parsedResponse = $this->parser->parse($guzzleResponse->getBody()->getContents());

		return $this->responseFactory->create($parsedResponse);
	}
}
