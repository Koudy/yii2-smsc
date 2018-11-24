<?php

use koudy\yii2\smsc\Message;
use yii\base\Component;

class MessageTest extends \PHPUnit\Framework\TestCase
{
    public function testInheritance()
    {
        $sender = new Message();
        $this->assertInstanceOf(Component::class, $sender);
    }

    public function testSetGetPhone()
    {
        $message = new Message();
        $phone = '::phone::';

        $this->assertSame($message, $message->setPhone($phone));
        $this->assertEquals($phone, $message->getPhone());
    }

    public function testSetGetText()
    {
        $message = new Message();
        $text = '::some text::';

        $this->assertSame($message, $message->setText($text));
        $this->assertEquals($text, $message->getText());
    }

    public function testSetAttributes()
    {
        $id = '::id::';
        $cnt = $this->getFaker()->numberBetween(1, 1000);
        $status = '::status::';

        $message = new Message();
        $attributes = [
            'id' => $id,
            'cnt' => $cnt,
            'phones' => [
                '0' => [
                    'phone' => '::phone::',
                    'mccmnc' => '::mccmnc::',
                    'cost' => '::cost::',
                    'status' => $status,
                    'error' => '::error::'
                ]
            ]
        ];

        $safeOnly = false;
        $message->setAttributes($attributes, $safeOnly);

        $this->assertSame($id, $message->getId());
        $this->assertSame($cnt, $message->getCnt());
        $this->assertSame($status, $message->getStatus());
    }

    public function testValidatePhoneWhenItIsEmpty()
    {
        $message = new Message();

        $this->assertFalse($message->validate('phone'));
        $this->assertEquals('Phone cannot be blank.', $message->getFirstError('phone'));
    }

    /**
     * @dataProvider wrongPhoneProvider
     * @param $phone
     * @param string $errorMessage
     */
    public function testValidatePhoneWhenOneWrongPhone($phone, string $errorMessage)
    {
        $message = new Message();
        $message->phone = $phone;

        $this->assertFalse($message->validate('phone'));
        $this->assertEquals($errorMessage, $message->getFirstError('phone'));
    }

    public function wrongPhoneProvider()
    {
        return [
            ['::some text::', 'Phone has an invalid format.'],
            [123, 'Phone is not valid phone number.'],
            [791612345678, 'Phone is not valid phone number.'],
            [+791612345678, 'Phone is not valid phone number.'],
            [891612345678, 'Phone is not valid phone number.'],
            [39161234567, 'Phone is not valid phone number.']
        ];
    }

    /**
     * @dataProvider correctPhoneProvider
     * @param $phone
     */
    public function testValidatePhoneWhenOneCorrectPhone($phone)
    {
        $message = new Message();
        $message->phone = $phone;

        $this->assertTrue($message->validate('phone'));
    }

    public function correctPhoneProvider()
    {
        return [
            ['+79161234567'],
            ['+7(916)1234567'],
            ['+7 916 123 45 67'],
            ['+7 (916) 123 45 67'],
            ['+7-916-123-45-67'],
            ['+7-(916)-123-45-67'],
            ['+7(916)-123-45-67'],
            ['+7 (916)-123-45-67'],

            ['79161234567'],
            ['7(916)1234567'],
            ['7 916 123 45 67'],
            ['7 (916) 123 45 67'],
            ['7-916-123-45-67'],
            ['7-(916)-123-45-67'],
            ['7(916)-123-45-67'],
            ['7 (916)-123-45-67'],

            ['89161234567'],
            ['8(916)1234567'],
            ['8 916 123 45 67'],
            ['8 (916) 123 45 67'],
            ['8-916-123-45-67'],
            ['8-(916)-123-45-67'],
            ['8(916)-123-45-67'],
            ['8 (916)-123-45-67'],
        ];
    }

    private function getFaker()
    {
        return Faker\Factory::create();
    }
}
