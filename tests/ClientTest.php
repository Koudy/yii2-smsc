<?php

use GuzzleHttp\Client as GuzzleClient;
use koudy\yii2\smsc\Client;
use koudy\yii2\smsc\interfaces\Response;
use koudy\yii2\smsc\interfaces\ResponseFactory;
use koudy\yii2\smsc\Parser;
use koudy\yii2\smsc\interfaces\Request;
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
        new Client(['guzzleClient' => '']);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage Invalid data type: stdClass. GuzzleHttp\Client is expected.
     */
    public function testCreateWhenWrongGuzzleClient()
    {
        new Client(['guzzleClient' => new Stdclass()]);
    }

    public function testCreateWhenCorrectGuzzleClientObject()
    {
        $guzzleClient = $this->createMock(GuzzleClient::class);
        $client = new Client(['guzzleClient' => $guzzleClient]);

        $this->assertSame($guzzleClient, $client->guzzleClient);
    }

    public function testCreateWhenCorrectGuzzleClientClassName()
    {
        $client = new Client(['guzzleClient' => GuzzleClientForClientTest::class,]);

        $this->assertInstanceOf(GuzzleClientForClientTest::class, $client->guzzleClient);
    }

    // Parser

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The required component is not specified.
     */
    public function testCreateWhenEmptyParser()
    {
        new Client(['parser' => '']);
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage Invalid data type: stdClass. koudy\yii2\smsc\Parser is expected.
     */
    public function testCreateWhenWrongParser()
    {
        new Client(['parser' => new Stdclass()]);
    }

    public function testCreateWhenCorrectParserObject()
    {
        $parser = $this->createMock(Parser::class);
        $client = new Client(['parser' => $parser]);

        $this->assertSame($parser, $client->parser);
    }

    public function testCreateWhenCorrectParserClassName()
    {
        $client = new Client(['parser' => ParserForClientTest::class,]);

        $this->assertInstanceOf(ParserForClientTest::class, $client->parser);
    }

    public function testSendRequest()
    {
        $url = '::url::';
        $method = '::method::';
        $requestParams = ['::params::'];
        $rawResponse = '::raw response::';
        $request = $this->createMock(Request::class);
        $request->method('getMethod')->willReturn($method);
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
            ->with('POST', $url . $method, $guzzleRequestParams)
            ->willReturn($guzzleResponse);

        $parser = $this->createMock(Parser::class);
        $parser->method('parse')->with($rawResponse)->willReturn($parsedResponse);

        $response = $this->createMock(Response::class);
        $responseFactory = $this->createMock(ResponseFactory::class);
        $responseFactory
            ->expects(self::once())
            ->method('createResponse')
            ->with($parsedResponse)
            ->willReturn($response);

        $clientConfig = [
            'guzzleClient' => $guzzleClient,
            'parser' => $parser
        ];

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['trigger'])
            ->setConstructorArgs([$clientConfig])
            ->getMock();
        $client
            ->expects(self::exactly(2))
            ->method('trigger')
            ->withConsecutive(
                ['before sending'],
                ['after sending']
            );

        $this->assertSame($response, $client->sendRequest($url, $request, $responseFactory));
        $this->assertContains($requestParams, Yii::getLogger()->messages[0]);
        $this->assertContains($rawResponse, Yii::getLogger()->messages[1]);
    }

    /**
     * @expectedException \koudy\yii2\smsc\exceptions\ClientException
     * @expectedExceptionMessage ::some error::
     */
    public function testSendRequestWhenGuzzleException()
    {
        $url = '::url::';
        $request = $this->createMock(Request::class);

        $guzzleClient = $this->createMock(GuzzleClient::class);
        $guzzleClient
            ->method('request')
            ->willThrowException(new \Exception('::some error::'));

        $parser = $this->createMock(Parser::class);
        $responseFactory = $this->createMock(ResponseFactory::class);

        $clientConfig = [
            'guzzleClient' => $guzzleClient,
            'parser' => $parser
        ];

        $client = $this->getMockBuilder(Client::class)
            ->setMethodsExcept(['sendRequest'])
            ->setConstructorArgs([$clientConfig])
            ->getMock();

        $client->sendRequest($url, $request, $responseFactory);
    }
}

class GuzzleClientForClientTest extends GuzzleClient
{
}

class ParserForClientTest extends Parser
{
}