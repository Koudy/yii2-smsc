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
    public $url = 'https://smsc.ru/sys/send.php';

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
        if (!$message->validate()) {
            throw new \Exception('Validation failed.');
        }

        $request = $this->sendFactory->createRequest(
            $message->getPhone(),
            $message->getText(),
            $this->login,
            $this->password
        );

        $this->sendRequest($message, $request, $this->sendFactory);
    }

    /**
     * @param Message $message
     * @throws exceptions\ClientException
     */
    public function getStatus(Message $message)
    {
        $request = $this->getStatusFactory->createRequest(
            $message->getId(),
            $message->getPhone(),
            $this->login,
            $this->password
        );

        $this->sendRequest($message, $request, $this->getStatusFactory);
    }

    /**
     * @param Message $message
     * @param Request $request
     * @param ResponseFactory $responseFactory
     * @throws exceptions\ClientException
     */
    private function sendRequest(Message $message, Request $request, ResponseFactory $responseFactory)
    {
        $response = $this->client->sendRequest($this->url, $request, $responseFactory);

        $sateOnly = false;
        $message->setAttributes($response->getData(), $sateOnly);
    }
}
