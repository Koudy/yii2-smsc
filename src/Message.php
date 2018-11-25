<?php

namespace koudy\yii2\smsc;

use mikk150\phonevalidator\PhoneNumberValidator;
use yii\base\Model;

class Message extends Model
{
    /**
     * @var string|array
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
            ['phone', PhoneNumberValidator::class, 'country' => 'RU']
        ];
    }

    /**
     * @param $phone
     * @return Message
     */
    public function setPhone($phone): Message
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getPhone()
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
     * @return string
     */
    public function getText(): string
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
     * @return string
     */
    public function getId(): string
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
