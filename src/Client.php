<?php

namespace koudy\yii2\smsc;

use GuzzleHttp\Client as GuzzleClient;
use Yii;
use yii\base\Component;

class Client extends Component
{
	/**
	 * @var GuzzleClient
	 */
	public $guzzleClient;

	/**
	 * @var Parser
	 */
	public $parser;

	/**
	 * @param string $url
	 * @param Request $request
	 * @return Response
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \yii\base\InvalidConfigException
	 * @throws \yii\di\NotInstantiableException
	 */
	public function sendRequest(string $url, Request $request): Response
	{
		$guzzleResponse = $this->guzzleClient->request('POST', $url, [
			'form_params' => $request->getRequestParams()
		]);

		$parsedResponse = $this->parser->parse($guzzleResponse->getBody()->getContents());

		return Yii::$container->get(Response::class, $parsedResponse);
	}
}
