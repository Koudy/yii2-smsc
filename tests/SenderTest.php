<?php

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use koudy\yii2\smsc\GetStatusRequest;
use koudy\yii2\smsc\GetStatusFactory;
use koudy\yii2\smsc\GetStatusResponse;
use koudy\yii2\smsc\Sender;
use koudy\yii2\smsc\Client;
use koudy\yii2\smsc\Message;
use koudy\yii2\smsc\SendRequest;
use koudy\yii2\smsc\SendFactory;
use koudy\yii2\smsc\SendResponse;
use koudy\yii2\smsc\SendResponseFactory;
use yii\base\Component;

class SenderTest extends \PHPUnit\Framework\TestCase
{
    public function testInheritance()
    {
        $sender = $this->createMock(Sender::class);
        $this->assertInstanceOf(Component::class, $sender);
    }

    public function testCreate()
    {
        $params = [
            'login' => '::some login::',
            'password' => '::some password::',
        ];
        $sender = Yii::$container->get(Sender::class, [2 => $params]);

        $this->assertInstanceOf(Sender::class, $sender);
        $this->assertInstanceOf(Client::class, $sender->client);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The required component is not specified.
     */
    public function testCreateWhenEmptyClient()
    {
        new Sender(
            $this->createMock(SendFactory::class),
            $this->createMock(GetStatusFactory::class),
            ['client' => '']
        );
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage Invalid data type: stdClass. koudy\yii2\smsc\Client is expected.
     */
    public function testCreateWhenWrongClient()
    {
        new Sender(
            $this->createMock(SendFactory::class),
            $this->createMock(GetStatusFactory::class),
            ['client' => new Stdclass()]
        );
    }

    public function testCreateWhenCorrectClientObject()
    {
        $client = $this->createMock(Client::class);
        $sender = new Sender(
            $this->createMock(SendFactory::class),
            $this->createMock(GetStatusFactory::class),
            [
                'client' => $client,
                'login' => '::some login::',
                'password' => '::some password::',
            ]
        );

        $this->assertSame($client, $sender->client);
    }

    public function testCreateWhenCorrectClientClassName()
    {
        $sender = new Sender(
            $this->createMock(SendFactory::class),
            $this->createMock(GetStatusFactory::class),
            [
                'client' => ClientForSenderTest::class,
                'login' => '::some login::',
                'password' => '::some password::',
            ]
        );

        $this->assertInstanceOf(ClientForSenderTest::class, $sender->client);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "url" property must be set.
     */
    public function testCreateWhenEmptyUrl()
    {
        new Sender(
            $this->createMock(SendFactory::class),
            $this->createMock(GetStatusFactory::class),
            [
                'client' => $this->createMock(Client::class),
                'url' => ''
            ]
        );
    }

    public function testCreateWhenUrlWithoutSlash()
    {
        $sender = new Sender(
            $this->createMock(SendFactory::class),
            $this->createMock(GetStatusFactory::class),
            [
                'client' => $this->createMock(Client::class),
                'url' => '::some url::',
                'login' => '::some login::',
                'password' => '::password::'
            ]
        );

        $this->assertEquals('::some url::/', $sender->url);
    }

    public function testCreateWhenUrlWithSlash()
    {
        $sender = new Sender(
            $this->createMock(SendFactory::class),
            $this->createMock(GetStatusFactory::class),
            [
                'client' => $this->createMock(Client::class),
                'url' => '::some url::/',
                'login' => '::some login::',
                'password' => '::password::'
            ]
        );

        $this->assertEquals('::some url::/', $sender->url);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "login" property must be set.
     */
    public function testCreateWhenEmptyLogin()
    {
        new Sender(
            $this->createMock(SendFactory::class),
            $this->createMock(GetStatusFactory::class),
            [
                'client' => $this->createMock(Client::class),
                'url' => '::some url::',
                'login' => ''
            ]
        );
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "password" property must be set.
     */
    public function testCreateWhenEmptyPassword()
    {
        new Sender(
            $this->createMock(SendFactory::class),
            $this->createMock(GetStatusFactory::class),
            [
                'client' => $this->createMock(Client::class),
                'url' => '::some url::',
                'login' => '::some login::',
                'password' => ''
            ]
        );
    }

    public function testDefaultUrl()
    {
        $sender = $this->createMock(Sender::class);
        $this->assertEquals('https://smsc.ru/sys/', $sender->url);
    }

    public function testCreateMessage()
    {
        $sender = $this->getMockBuilder(Sender::class)
            ->setMethodsExcept(['createMessage'])
            ->disableOriginalConstructor()
            ->getMock();

        $message = $sender->createMessage();

        $this->assertInstanceOf(Message::class, $message);
    }

    public function testSendUnit()
    {
        $phone = '::phone::';
        $text = '::text::';

        $login = '::login::';
        $password = '::password::';

        $url = '::url::/';

        $responseData = ['::responase data::'];

        $scenario = 'send';

        $sateOnly = false;
        $message = $this->createMock(Message::class);
        $message->method('getPhone')->willReturn($phone);
        $message->method('getText')->willReturn($text);
        $message->method('validate')->willReturn(true);
        $message->expects(self::once())->method('setScenario')->with($scenario);
        $message->expects(self::once())->method('setAttributes')->with($responseData, $sateOnly);

        $sendRequest = $this->createMock(SendRequest::class);

        $sendFactory = $this->createMock(SendFactory::class);
        $sendFactory
            ->method('createRequest')
            ->with($phone, $text, $login, $password)
            ->willReturn($sendRequest);

        $response = $this->createMock(SendResponse::class);
        $response->method('getData')->willReturn($responseData);

        $client = $this->createMock(Client::class);
        $client->method('sendRequest')->with($url, $sendRequest, $sendFactory)->willReturn($response);

        $senderConfig = [
            'login' => $login,
            'password' => $password,
            'url' => $url,
            'client' => $client
        ];

        $getStatusFactory = $this->createMock(GetStatusFactory::class);

        $sender = new Sender($sendFactory, $getStatusFactory, $senderConfig);
        $sender->send($message);
    }

    public function testSendIntegration()
    {
        $login = '::login::';
        $password = '::password::';
        $url = 'https://smsc.ru/sys/send.php';

        $phone = '+79161234567';
        $text = '::text::';

        $id = '::id::';
        $cnt = $this->getFaker()->numberBetween(1, 1000);
        $status = '::status::';

        $guzzleResponse = new \GuzzleHttp\Psr7\Response(
            200,
            ['X-Foo' => 'Bar'],
            json_encode([
                'id' => $id,
                'cnt' => $cnt,
                'phones' => [
                    '0' => [
                        'phone' => '::phone::',
                        'mccmnc' => '::mccmnc::',
                        'cost' => '::cost::',
                        'status' => $status,
                        'error' => '::error::'
                    ]
                ]
            ])
        );

        $mock = new MockHandler([
            $guzzleResponse
        ]);

        $handler = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handler]);

        Yii::$container->set(GuzzleClient::class, $guzzleClient);

        /**
         * @var Sender $sender
         */
        $sender = Yii::createObject([
            'class' => Sender::class,
            'login' => $login,
            'password' => $password,
            'url' => $url
        ]);

        $message = $sender->createMessage();
        $message->setPhone($phone);
        $message->setText($text);

        $sender->send($message);

        Yii::$container->clear(GuzzleClient::class);

        $this->assertEquals($id, $message->getId());
        $this->assertEquals($cnt, $message->getCount());
        $this->assertEquals($status, $message->getStatus());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Validation failed.
     */
    public function testSendWhenMessageValidationIsFailed()
    {
        $message = $this->createMock(Message::class);
        $message->method('validate')->willReturn(false);

        $sender = $this
            ->getMockBuilder(Sender::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['send'])
            ->getMock();
        $sender->send($message);
    }

    public function testGetStatusUnit()
    {
        $id = '::id::';
        $phone = '+79161234567';

        $login = '::login::';
        $password = '::password::';

        $url = '::url::/';

        $responseData = ['::response data::'];

        $scenario = 'get status';

        $safeOnly = false;
        $message = $this->createMock(Message::class);
        $message->method('getId')->willReturn($id);
        $message->method('getPhone')->willReturn($phone);
        $message->method('validate')->willReturn(true);
        $message->expects(self::once())->method('setScenario')->with($scenario);
        $message->expects(self::once())->method('setAttributes')->with($responseData, $safeOnly);

        $getStatusRequest = $this->createMock(GetStatusRequest::class);

        $getStatusFactory = $this->createMock(GetStatusFactory::class);
        $getStatusFactory
            ->method('createRequest')
            ->with($id, $phone, $login, $password)
            ->willReturn($getStatusRequest);

        $response = $this->createMock(GetStatusResponse::class);
        $response->method('getData')->willReturn($responseData);

        $client = $this->createMock(Client::class);
        $client->method('sendRequest')->with($url, $getStatusRequest, $getStatusFactory)->willReturn($response);

        $senderConfig = [
            'login' => $login,
            'password' => $password,
            'url' => $url,
            'client' => $client
        ];

        $sendRequestFactory = $this->createMock(SendFactory::class);

        $sender = new Sender($sendRequestFactory, $getStatusFactory, $senderConfig);
        $sender->getStatus($message);
    }

    public function testGetStatusIntegration()
    {
        $login = '::login::';
        $password = '::password::';
        $url = 'https://smsc.ru/sys/send.php';

        $id = '::id::';
        $phone = '+79161234567';

        $status = '::status::';

        $guzzleResponse = new \GuzzleHttp\Psr7\Response(
            200,
            ['X-Foo' => 'Bar'],
            json_encode([
                'status' => $status,
                'last_date' => '::last_date>::',
                'last_timestamp' => '::last_timestamp::',
                'err' => '::err::'
            ])
        );

        $mock = new MockHandler([
            $guzzleResponse
        ]);

        $handler = HandlerStack::create($mock);
        $guzzleClient = new GuzzleClient(['handler' => $handler]);

        Yii::$container->set(GuzzleClient::class, $guzzleClient);

        /**
         * @var Sender $sender
         */
        $sender = Yii::createObject([
            'class' => Sender::class,
            'login' => $login,
            'password' => $password,
            'url' => $url
        ]);

        $message = $sender->createMessage();
        $message->setId($id);
        $message->setPhone($phone);

        $sender->getStatus($message);

        Yii::$container->clear(GuzzleClient::class);

        $this->assertEquals($status, $message->getStatus());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Validation failed.
     */
    public function testGetStatusWhenMessageValidationIsFailed()
    {
        $message = $this->createMock(Message::class);
        $message->method('validate')->willReturn(false);

        $sender = $this
            ->getMockBuilder(Sender::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['getStatus'])
            ->getMock();
        $sender->getStatus($message);
    }

    private function getFaker()
    {
        return Faker\Factory::create();
    }
}

class ClientForSenderTest extends Client
{
}
