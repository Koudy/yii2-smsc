<?php

namespace koudy\yii2\smsc;

use Yii;
use yii\base\Model;

class ResponseFactory
{
    /**
     * @param $parsedResponse
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function create($parsedResponse): Response
    {
        $response = Yii::$container->get(Response::class);
        $response->setAttributes($parsedResponse);

        return $response;
    }
}
