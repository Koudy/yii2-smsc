<?php

namespace koudy\yii2\smsc;

use GuzzleHttp\Client as GuzzleClient;
use koudy\yii2\smsc\exceptions\ClientException;
use koudy\yii2\smsc\interfaces\Request;
use koudy\yii2\smsc\interfaces\Response;
use koudy\yii2\smsc\interfaces\ResponseFactory;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;

class Client extends Component
{
    const EVENT_BEFORE_SENDING = 'before sending';

    const EVENT_AFTER_SENDING = 'after sending';

    /**
     * @var MessageSaver
     */
    private $messageSaver;

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
     * @param MessageSaver $messageSaver
     * @param array $config
     */
    public function __construct(MessageSaver $messageSaver, array $config = [])
    {
        $this->messageSaver = $messageSaver;

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

    public function sendRequest(
        string $url,
        Request $request,
        ResponseFactory $responseFactory,
        bool $useFileTransport = false,
        string $fileTransportPath = null,
        string $fileName = null
    ): Response
    {
        $requestParams = $request->getRequestParams();
        $this->trigger(self::EVENT_BEFORE_SENDING);
        Yii::info($requestParams);

        $rawResponse = null;

        try {
            if ($useFileTransport) {
                $rawResponse = $this->messageSaver->save(
                    $request->getMethod(),
                    $requestParams,
                    $fileTransportPath,
                    $fileName
                );
            } else {
                $guzzleResponse = $this->guzzleClient->request('POST', $url . $request->getMethod(), [
                    'form_params' => $requestParams
                ]);
                $rawResponse = $guzzleResponse->getBody()->getContents();
            }
        } catch (\Exception $exception) {
            throw new ClientException($exception->getMessage());
        }

        $this->trigger(self::EVENT_AFTER_SENDING);

        Yii::info($rawResponse);
        $parsedResponse = $this->parser->parse($rawResponse);

        return $responseFactory->createResponse($parsedResponse);
    }
}
