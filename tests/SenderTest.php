<?php

use koudy\yii2\smsc\Sender;
use koudy\yii2\smsc\Client;
use koudy\yii2\smsc\Message;
use koudy\yii2\smsc\Request;
use koudy\yii2\smsc\RequestFactory;
use koudy\yii2\smsc\Response;
use yii\base\Component;

class SenderTest extends \PHPUnit\Framework\TestCase
{
    public function testInheritance()
    {
        $sender = $this->createMock(Sender::class);
        $this->assertInstanceOf(Component::class, $sender);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage Wrong Client.
     */
    public function testCreateWhenWrongClient()
    {
        new Sender($this->createMock(RequestFactory::class), ['client' => new Stdclass()]);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "url" property must be set.
     */
    public function testCreateWhenEmptyUrl()
    {
        new Sender(
            $this->createMock(RequestFactory::class),
            [
                'client' => $this->createMock(Client::class),
                'url' => ''
            ]
        );
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "login" property must be set.
     */
    public function testCreateWhenEmptyLogin()
    {
        new Sender(
            $this->createMock(RequestFactory::class),
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
            $this->createMock(RequestFactory::class),
            [
                'client' => $this->createMock(Client::class),
                'url' => '::some url::',
                'login' => '::some login::',
                'password' => ''
            ]
        );
    }

    public function testUrl()
    {
        $sender = $this->createMock(Sender::class);
        $this->assertEquals('https://smsc.ru/sys/send.php', $sender->url);
    }

    public function testSend()
    {
        $phone = '::phone::';
        $text = '::text::';

        $login = '::login::';
        $password = '::password::';

        $url = '::url::';

        $message = $this->createMock(Message::class);
        $message->method('getPhone')->willReturn($phone);
        $message->method('getText')->willReturn($text);
        $message->method('validate')->willReturn(true);

        $request = $this->createMock(Request::class);

        $requestFactory = $this->createMock(RequestFactory::class);
        $requestFactory
            ->method('create')
            ->with($phone, $text, $login, $password)
            ->willReturn($request);

        $response = $this->createMock(Response::class);

        $client = $this->createMock(Client::class);
        $client->method('sendRequest')->with($url, $request)->willReturn($response);

        $senderConfig = [
            'login' => $login,
            'password' => $password,
            'url' => $url,
            'client' => $client
        ];

        $sender = new Sender($requestFactory, $senderConfig);

        $this->assertSame($response, $sender->send($message));
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
}
