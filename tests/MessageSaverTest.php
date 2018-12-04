<?php

use koudy\yii2\smsc\interfaces\Request;
use koudy\yii2\smsc\MessageSaver;

class MessageSaverTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->removeFiles(Yii::getAlias('@smscunit/runtime/messages'));
        $this->removeFiles(Yii::getAlias('@runtime/smsc'));
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->removeFiles(Yii::getAlias('@smscunit/runtime/messages'));
        $this->removeFiles(Yii::getAlias('@runtime/smsc'));
    }

    private function removeFiles($path)
    {
        array_map('unlink', glob($path . '/*'));
    }

    /**
     * @dataProvider methodsProvider
     * @param string $method
     * @param array $requestParams
     * @param string $response
     * @throws Exception
     */
	public function testSave(string $method, array $requestParams, string $response)
	{
	    $fileTransportPath = '@smscunit/runtime/messages';
        $fileName = 'message.txt';
        $filePath = Yii::getAlias($fileTransportPath) . '/' . $fileName;

		$messageSaver = new MessageSaver();

		$this->assertEquals($response, $messageSaver->save($method, $requestParams, $fileTransportPath, $fileName));

        $this->assertTrue(is_file($filePath));
        $this->assertStringEqualsFile($filePath, print_r($requestParams, true));
	}

    /**
     * @dataProvider methodsProvider
     * @param string $method
     * @param array $requestParams
     * @param string $response
     * @throws Exception
     */
	public function testSaveWhenNoFileTransportPathAndNoFileName(string $method, array $requestParams, string $response)
	{
	    $defaultFileTransportPath = '@runtime/smsc';

		$messageSaver = new MessageSaver();

		$this->assertEquals($response, $messageSaver->save($method, $requestParams));

        $filePath = glob(Yii::getAlias($defaultFileTransportPath) . '/*.txt')[0];

        $this->assertTrue(is_file($filePath));
        $this->assertStringEqualsFile($filePath, print_r($requestParams, true));
	}

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Unknown method: unknown method
     */
	public function testSaveWhenUnknownMethod()
    {
        $messageSaver = new MessageSaver();
        $messageSaver->save('unknown method', []);
    }

    public function methodsProvider()
    {

        return [
            [
                Request::METHOS_SEND,
                [
                    'login' => '::login::',
                    'psw' => '::password::',
                    'phones' => '::phone::',
                    'mes' => '::text::',
                    'fmt' => '::format::',
                    'op' => '::op::',
                    'charset' => '::charset::'
                ],
                json_encode([
                    'id' => '::id::',
                    'cnt' => 1,
                    'phones' => [
                        '0' => [
                            'phone' => '::phone::',
                            'mccmnc' => '::mccmnc::',
                            'cost' => '::cost::',
                            'status' => '::status::',
                            'error' => '::error::'
                        ]
                    ]
                ])
            ],
            [
                Request::METHOS_GET_STATUS,
                [
                    'login' => '::login::',
                    'psw' => '::password::',
                    'id' => '::id::',
                    'phone' => '::phone::',
                    'fmt' => '::fmt::',
                    'charset' => '::charset::'
                ],
                json_encode([
                    'status' => '::status::',
                    'last_date' => '::last_date>::',
                    'last_timestamp' => '::last_timestamp::',
                    'err' => '::err::'
                ])
            ],
        ];
    }
}
