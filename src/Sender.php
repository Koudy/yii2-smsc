<?php

namespace koudy\yii2\smsc;

use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;

class Sender extends Component
{
    /**
     * @var RequestFactory
     */
    private $requestFactory;

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

    public function createMessage(): Message
    {
        return new Message();
    }

    public function send(Message $message)
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

        $sateOnly = false;
        $message->setAttributes($this->client->sendRequest($this->url, $request), $sateOnly);
    }
}
