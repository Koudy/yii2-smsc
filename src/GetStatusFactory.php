<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Response;
use koudy\yii2\smsc\interfaces\ResponseFactory;

class GetStatusFactory implements ResponseFactory
{
    public function createRequest(string $id, string $phone, string $login, string $password): GetStatusRequest
    {
        return new GetStatusRequest($id, $phone, $login, $password);
    }

    public function createResponse($parsedResponse): Response
    {
        /**
         * @var Response $response
         */
        $response = \Yii::$container->get(GetStatusResponse::class);
        $response->setAttributes($parsedResponse, false);

        return $response;
    }
}
