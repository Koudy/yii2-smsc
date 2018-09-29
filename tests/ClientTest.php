<?php

use GuzzleHttp\Client as GuzzleClient;
use koudy\yii2\smsc\Client;
use koudy\yii2\smsc\interfaces\Client as ClientInterface;
use koudy\yii2\smsc\interfaces\Parser;
use koudy\yii2\smsc\interfaces\Request;
use koudy\yii2\smsc\interfaces\Response;
use koudy\yii2\smsc\interfaces\ResponseFactory;

class ClientTest extends \PHPUnit\Framework\TestCase
{
	public function testInterface()
	{
	    $client = new Client(
		    $this->createMock(GuzzleClient::class),
		    $this->createMock(Parser::class),
		    $this->createMock(ResponseFactory::class)
	    );
	    $this->assertInstanceOf(ClientInterface::class, $client);
	}

	public function testSendRequest()
	{
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
			->with('POST', 'https://smsc.ru/sys/send.php', $guzzleRequestParams)
			->willReturn($guzzleResponse);

		$response = $this->createMock(Response::class);

		$parser = $this->createMock(Parser::class);
		$parser->method('parse')->with($rawResponse)->willReturn($parsedResponse);

		$responseFactory = $this->createMock(ResponseFactory::class);
		$responseFactory->method('create')->with($parsedResponse)->willReturn($response);

		$client = new Client($guzzleClient, $parser, $responseFactory);

		$this->assertSame($response, $client->sendRequest($request));
	}
}
