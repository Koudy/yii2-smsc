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
        $sender = new Client($this->createMock(ResponseFactory::class));
        $this->assertInstanceOf(Component::class, $sender);
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