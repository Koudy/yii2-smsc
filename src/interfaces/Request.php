<?php

namespace koudy\yii2\smsc\interfaces;

interface Request
{
    const RESPONSE_JSON_FORMAT = 3;

    const CHARSET = 'utf-8';

    public function getRequestParams(): array;
}
