<?php

namespace koudy\yii2\smsc;

abstract class Request
{
    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    public function __construct(
        string $login,
        string $password
    )
    {
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @inheritdoc
     */
    public function getAuthorizationData(): array
    {
        return [
            'login' => $this->login,
            'psw' => $this->password
        ];
    }
}
