<?php

namespace koudy\yii2\smsc\interfaces;

interface ResponseFactory
{
    /**
     * @param $parsedResponse
     * @return Response
     */
    public function createResponse($parsedResponse): Response;
}
