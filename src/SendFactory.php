<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Request;
use koudy\yii2\smsc\interfaces\Response;
use koudy\yii2\smsc\interfaces\ResponseFactory;

class SendFactory implements ResponseFactory
{
    /**
     * @param string $phone
     * @param string $text
     * @param string $login
     * @param string $password
     * @return SendRequest
     */
    public function createRequest(string $phone, string $text, string $login, string $password): Request
    {
        return new SendRequest($phone, $text, $login, $password);
    }

    /**
     * @param $parsedResponse
     * @return SendResponse
     * @throws \yii\base\InvalidConfigException
     */
    public function createResponse($parsedResponse): Response
    {
        /**
         * @var Response $response
         */
        $response = \Yii::$container->get(SendResponse::class);
        $response->setAttributes($parsedResponse, false);

        return $response;
    }
}
