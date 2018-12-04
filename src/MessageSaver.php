<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Request;

class MessageSaver
{
	public function save(
	    string $method,
        array $requestParams,
        string $fileTransportPath = '@runtime/smsc',
        string $fileName = null
    ): string
	{
	    $response = null;

	    switch ($method) {
            case Request::METHOS_SEND:
                $response = json_encode([
                    'id' => '::id::',
                    'cnt' => 1,
                    'phones' => [
                        '0' => [
                            'phone' => $requestParams['phones'],
                            'mccmnc' => '::mccmnc::',
                            'cost' => '::cost::',
                            'status' => '::status::',
                            'error' => '::error::'
                        ]
                    ]
                ]);
                break;
            case Request::METHOS_GET_STATUS:
                $response = json_encode([
                    'status' => '::status::',
                    'last_date' => '::last_date>::',
                    'last_timestamp' => '::last_timestamp::',
                    'err' => '::err::'
                ]);
                break;
            default:
                throw new \Exception('Unknown method: ' . $method);
        }

        if (!$fileName) {
            $fileName = time() . '.txt';
        }

        $path = \Yii::getAlias($fileTransportPath);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $file = $path . '/' . $fileName;

        file_put_contents($file, print_r($requestParams, true));

        return $response;
	}
}
