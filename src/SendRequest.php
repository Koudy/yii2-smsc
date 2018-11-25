<?php

namespace koudy\yii2\smsc;

class SendRequest implements interfaces\Request
{
    //Признак необходимости добавления в ответ сервера информации по каждому номеру.
    const OP = 1;

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
            'fmt' => self::RESPONSE_JSON_FORMAT,
            'op' => self::OP,
            'charset' => self::CHARSET
        ];
    }
}
