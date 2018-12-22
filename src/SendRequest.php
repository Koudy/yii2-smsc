<?php

namespace koudy\yii2\smsc;

class SendRequest extends Request implements interfaces\Request
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
     * SendRequest constructor.
     * @param $phone
     * @param string $text
     * @param string $login
     * @param string $password
     */
    public function __construct(
        $phone,
        string $text,
        string $login,
        string $password
    )
    {
        $this->phone = $phone;
        $this->text = $text;

        parent::__construct($login, $password);
    }

    /**
     * @inheritdoc
     */
    public function getRequestParams(): array
    {
        return [
            'phones' => $this->phone,
            'mes' => $this->text,
            'fmt' => self::RESPONSE_JSON_FORMAT,
            'op' => self::OP,
            'charset' => self::CHARSET
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMethod(): string
    {
        return self::METHOS_SEND;
    }
}
