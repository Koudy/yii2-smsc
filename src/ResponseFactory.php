<?php

namespace koudy\yii2\smsc;

use Yii;

class ResponseFactory
{
    /**
     * @param $parsedResponse
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function create($parsedResponse): Response
    {
        return Yii::createObject(Response::class, [$parsedResponse]);
    }
}
