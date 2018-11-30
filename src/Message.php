<?php

namespace koudy\yii2\smsc;

use mikk150\phonevalidator\PhoneNumberValidator;
use yii\base\Model;

class Message extends Model
{
    const SCENARIO_SEND = 'send';

    const SCENARIO_GET_STATUS = 'get status';

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
    public $id;

    /**
     * @var int
     */
    public $count;

    /**
     * @var string
     */
    public $status;

    public function rules()
    {
        return [
            ['phone', 'required'],
            ['phone', PhoneNumberValidator::class, 'country' => 'RU'],
            ['text', 'required', 'on' => self::SCENARIO_SEND],
            ['id', 'required', 'on' => self::SCENARIO_GET_STATUS]
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_SEND => ['phone', 'text'],
            self::SCENARIO_GET_STATUS => ['id', 'phone']
        ];
    }

    /**
     * @param string $phone
     * @return Message
     */
    public function setPhone(string $phone): Message
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function setText(string $text): Message
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $id
     * @return Message
     */
    public function setId(string $id): Message
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
}
