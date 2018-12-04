<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Request;
use koudy\yii2\smsc\interfaces\ResponseFactory;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;

class Sender extends Component
{
    /**
     * @var SendFactory
     */
    private $sendFactory;

    /**
     * @var GetStatusFactory
     */
    private $getStatusFactory;

    /**
     * @var Client|string
     */
    public $client = Client::class;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $url = 'https://smsc.ru/sys/';

    /**
     * @var bool
     */
    public $useFileTransport = false;

    /**
     * @var string
     */
    public $fileTransportPath = '@runtime/smsc';

    /**
     * @var string
     */
    public $fileName = 'message.txt';

    /**
     * Sender constructor.
     * @param SendFactory $responseFactory
     * @param GetStatusFactory $getStatusRequestFactory
     * @param array $config
     */
    public function __construct(
        SendFactory $responseFactory,
        GetStatusFactory $getStatusRequestFactory,
        array $config = []
    )
    {
        $this->sendFactory = $responseFactory;
        $this->getStatusFactory = $getStatusRequestFactory;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->client = Instance::ensure($this->client, Client::class);

        if (!$this->url) {
            throw new InvalidConfigException('The "url" property must be set.');
        }

        if (!$this->login) {
            throw new InvalidConfigException('The "login" property must be set.');
        }

        if (!$this->password) {
            throw new InvalidConfigException('The "password" property must be set.');
        }

        $this->url = rtrim($this->url, '/') . '/';
    }

    /**
     * @return Message
     */
    public function createMessage(): Message
    {
        return new Message();
    }

    /**
     * @param Message $message
     * @throws exceptions\ClientException
     */
    public function send(Message $message)
    {
        $message->setScenario(Message::SCENARIO_SEND);
        if (!$message->validate()) {
            throw new \Exception('Validation failed.');
        }

        $request = $this->sendFactory->createRequest(
            $message->getPhone(),
            $message->getText(),
            $this->login,
            $this->password
        );

        $this->sendRequest($message,
            $request,
            $this->sendFactory,
            $this->useFileTransport,
            $this->fileTransportPath,
            $this->fileName
        );
    }

    /**
     * @param Message $message
     * @throws exceptions\ClientException
     */
    public function getStatus(Message $message)
    {
        $message->setScenario(Message::SCENARIO_GET_STATUS);
        if (!$message->validate()) {
            throw new \Exception('Validation failed.');
        }

        $request = $this->getStatusFactory->createRequest(
            $message->getId(),
            $message->getPhone(),
            $this->login,
            $this->password
        );

        $this->sendRequest(
            $message,
            $request,
            $this->getStatusFactory,
            $this->useFileTransport,
            $this->fileTransportPath,
            $this->fileName
        );
    }

    /**
     * @param Message $message
     * @param Request $request
     * @param ResponseFactory $responseFactory
     * @param bool $useFileTransport
     * @param string $fileTransportPath
     * @param string $fileName
     * @throws exceptions\ClientException
     */
    private function sendRequest(
        Message $message,
        Request $request,
        ResponseFactory $responseFactory,
        bool $useFileTransport,
        string $fileTransportPath,
        string $fileName
    )
    {
        $response = $this->client->sendRequest(
            $this->url,
            $request,
            $responseFactory,
            $useFileTransport,
            $fileTransportPath,
            $fileName
        );

        $sateOnly = false;
        $message->setAttributes($response->getData(), $sateOnly);
    }
}
