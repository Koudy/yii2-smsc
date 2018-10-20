<?php

namespace koudy\yii2\smsc;

class Request
{
    const RESPONSE_JSON_FORMAT = 3;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    public function __construct(
        $phone,
        string $text,
        string $login,
        string $password
    )
    {
        $this->phone = $phone;
        $this->text = $text;
        $this->login = $login;
        $this->password = $password;
    }

    public function getRequestParams(): array
    {
        return [
            'login' => $this->login,
            'psw' => $this->password,
            'phones' => $this->phone,
            'mes' => $this->text,
            'fmt' => self::RESPONSE_JSON_FORMAT
        ];
    }
}