<?php

use GuzzleHttp\Client as GuzzleClient;
use koudy\yii2\smsc\Client;
use koudy\yii2\smsc\Parser;
use koudy\yii2\smsc\Request;
use koudy\yii2\smsc\Response;
use koudy\yii2\smsc\ResponseFactory;
use yii\base\Component;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    public function testInheritance()
    {
        $client = $this->createMock(Client::class);
        $this->assertInstanceOf(Component::class, $client);
    }

    public function testCreate()
    {
        $client = Yii::$container->get(Client::class);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(GuzzleClient::class, $client->guzzleClient);
        $this->assertInstanceOf(Parser::class, $client->parser);
    }

    // GuzzleClient

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The required component is not specified.
     */
    public function testCreateWhenEmptyGuzzleClient()
    {
        new Client($this->createMock(ResponseFactory::class), ['guzzleClient' => '']);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage Invalid data type: stdClass. GuzzleHttp\Client is expected.
     */
    public function testCreateWhenWrongGuzzleClient()
    {
        new Client($this->createMock(ResponseFactory::class), ['guzzleClient' => new Stdclass()]);
    }

    public function testCreateWhenCorrectGuzzleClientObject()
    {
        $guzzleClient = $this->createMock(GuzzleClient::class);
        $client = new Client(
            $this->createMock(ResponseFactory::class),
            [
                'guzzleClient' => $guzzleClient
            ]
        );

        $this->assertSame($guzzleClient, $client->guzzleClient);
    }

    public function testCreateWhenCorrectGuzzleClientClassName()
    {
        $client = new Client(
            $this->createMock(ResponseFactory::class),
            [
                'guzzleClient' => GuzzleClientForClientTest::class,
            ]
        );

        $this->assertInstanceOf(GuzzleClientForClientTest::class, $client->guzzleClient);
    }

    // Parser

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The required component is not specified.
     */
    public function testCreateWhenEmptyParser()
    {
        new Client($this->createMock(ResponseFactory::class), ['parser' => '']);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage Invalid data type: stdClass. koudy\yii2\smsc\Parser is expected.
     */
    public function testCreateWhenWrongParser()
    {
        new Client($this->createMock(ResponseFactory::class), ['parser' => new Stdclass()]);
    }

    public function testCreateWhenCorrectParserObject()
    {
        $parser = $this->createMock(Parser::class);
        $client = new Client(
            $this->createMock(ResponseFactory::class),
            [
                'parser' => $parser
            ]
        );

        $this->assertSame($parser, $client->parser);
    }

    public function testCreateWhenCorrectParserClassName()
    {
        $client = new Client(
            $this->createMock(ResponseFactory::class),
            [
                'parser' => ParserForClientTest::class,
            ]
        );

        $this->assertInstanceOf(ParserForClientTest::class, $client->parser);
    }

    public function testSendRequest()
    {
        $url = '::url::';
        $requestParams = ['::params::'];
        $rawResponse = '::raw response::';
        $request = $this->createMock(Request::class);
        $request->method('getRequestParams')->willReturn($requestParams);

        $guzzleRequestParams = [
            'form_params' => $requestParams
        ];

        $parsedResponse = ['::parsed response::'];

        $body = $this->createMock(GuzzleHttp\Psr7\Stream::class);
        $body->method('getContents')->willReturn($rawResponse);

        $guzzleResponse = $this->createMock(GuzzleHttp\Psr7\Response::class);
        $guzzleResponse
            ->method('getBody')
            ->willReturn($body);

        $guzzleClient = $this->createMock(GuzzleClient::class);
        $guzzleClient
            ->expects(self::once())
            ->method('request')
            ->with('POST', $url, $guzzleRequestParams)
            ->willReturn($guzzleResponse);

        $response = $this->createMock(Response::class);

        $responseFactory = $this->createMock(ResponseFactory::class);
        $responseFactory->method('create')->with($parsedResponse)->willReturn($response);

        $parser = $this->createMock(Parser::class);
        $parser->method('parse')->with($rawResponse)->willReturn($parsedResponse);

        $clientConfig = [
            'guzzleClient' => $guzzleClient,
            'parser' => $parser
        ];

        $client = new Client($responseFactory, $clientConfig);

        $this->assertSame($response, $client->sendRequest($url, $request));
    }
}

class GuzzleClientForClientTest extends GuzzleClient
{
}

class ParserForClientTest extends Parser
{
}