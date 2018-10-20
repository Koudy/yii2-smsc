<?php

namespace koudy\yii2\smsc;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class Sender extends Component
{
    /**
     * @var RequestFactory
     */
    private $requestFactory;

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
    public $password;

    /**
     * @var string
     */
    public $url = 'https://smsc.ru/sys/send.php';

    /**
     * @param RequestFactory $requestFactory
     * @param array $config
     */
    public function __construct(
        RequestFactory $requestFactory,
        array $config = []
    )
    {
        $this->requestFactory = $requestFactory;

        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        if (!$this->client instanceof Client) {
            throw new InvalidConfigException('Wrong Client.');
        }

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
     * @param Message $message
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function send(Message $message): Response
    {
        if (!$message->validate()) {
            throw new \Exception('Validation failed.');
        }

        $request = $this->requestFactory->create(
            $message->getPhone(),
            $message->getText(),
            $this->login,
            $this->password
        );

        return $this->client->sendRequest($this->url, $request);
    }
}
