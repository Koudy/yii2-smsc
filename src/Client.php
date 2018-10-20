<?php

namespace koudy\yii2\smsc;

use GuzzleHttp\Client as GuzzleClient;
use Yii;
use yii\base\Component;

class Client extends Component
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var GuzzleClient
     */
    public $guzzleClient;

    /**
     * @var Parser
     */
    public $parser;

    /**
     * Client constructor.
     * @param ResponseFactory $responseFactory
     * @param array $config
     */
    public function __construct(
        ResponseFactory $responseFactory,
        array $config = []
    )
    {
        $this->responseFactory = $responseFactory;

        parent::__construct($config);
    }

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

        return $this->responseFactory->create($parsedResponse);
    }
}
