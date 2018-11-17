<?php

namespace koudy\yii2\smsc;

use GuzzleHttp\Client as GuzzleClient;
use koudy\yii2\smsc\exceptions\ClientException;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;

class Client extends Component
{
    const EVENT_BEFORE_SENDING = 'before sending';

    const EVENT_AFTER_SENDING = 'after sending';

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var GuzzleClient
     */
    public $guzzleClient = GuzzleClient::class;

    /**
     * @var Parser
     */
    public $parser = Parser::class;

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
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->guzzleClient = Instance::ensure($this->guzzleClient, GuzzleClient::class);

        $this->parser = Instance::ensure($this->parser, Parser::class);
    }

    /**
     * @param string $url
     * @param Request $request
     * @return Response
     * @throws ClientException
     * @throws InvalidConfigException
     */
    public function sendRequest(string $url, Request $request): Response
    {
        $this->trigger(self::EVENT_BEFORE_SENDING);

        try {
            $guzzleResponse = $this->guzzleClient->request('POST', $url, [
                'form_params' => $request->getRequestParams()
            ]);
        } catch (\Exception $exception) {
            throw new ClientException($exception->getMessage());
        }

        $this->trigger(self::EVENT_AFTER_SENDING);

        $parsedResponse = $this->parser->parse($guzzleResponse->getBody()->getContents());

        return $this->responseFactory->create($parsedResponse);
    }
}
