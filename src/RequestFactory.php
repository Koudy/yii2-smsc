<?php

namespace koudy\yii2\smsc;

class RequestFactory
{
    /**
     * @param string $phone
     * @param string $text
     * @param string $login
     * @param string $password
     * @return Request
     */
    public function create(string $phone, string $text, string $login, string $password): Request
    {
        return new Request($phone, $text, $login, $password);
    }
}
