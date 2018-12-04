<?php

namespace koudy\yii2\smsc;

class GetStatusRequest implements interfaces\Request
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    public function __construct(
        string $id,
        $phone,
        string $login,
        string $password
    )
    {
        $this->id = $id;
        $this->phone = $phone;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @inheritdoc
     */
    public function getRequestParams(): array
    {
        return [
            'login' => $this->login,
            'psw' => $this->password,
            'id' => $this->id,
            'phone' => $this->phone,
            'fmt' => self::RESPONSE_JSON_FORMAT,
            'charset' => self::CHARSET
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMethod(): string
    {
        return self::METHOS_GET_STATUS;
    }
}
