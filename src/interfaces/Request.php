<?php

namespace koudy\yii2\smsc\interfaces;

interface Request
{
    const RESPONSE_JSON_FORMAT = 3;

    const CHARSET = 'utf-8';

    const METHOS_SEND = 'send.php';

    const METHOS_GET_STATUS = 'status.php';

    /**
     * @return array
     */
    public function getRequestParams(): array;

    /**
     * @return string
     */
    public function getMethod(): string;
}
