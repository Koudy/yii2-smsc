<?php

namespace koudy\yii2\smsc;

class GetStatusRequest extends Request implements interfaces\Request
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
     * GetStatusRequest constructor.
     * @param string $id
     * @param $phone
     * @param string $login
     * @param string $password
     */
    public function __construct(
        string $id,
        $phone,
        string $login,
        string $password
    )
    {
        $this->id = $id;
        $this->phone = $phone;

        parent::__construct($login, $password);
    }

    /**
     * @inheritdoc
     */
    public function getRequestParams(): array
    {
        return [
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
