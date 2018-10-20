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

    public function rules()
    {
        return [
            ['phone', 'required'],
            ['phone', PhoneNumberValidator::class, 'country' => 'RU']
        ];
    }

    /**
     * @param $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
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
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}
