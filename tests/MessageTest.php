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

    public function testGetPhoneWhenItIsNull()
    {
        $message = new Message();

        $this->assertNull($message->getPhone());
    }

    public function testSetGetText()
    {
        $message = new Message();
        $text = '::some text::';

        $this->assertSame($message, $message->setText($text));
        $this->assertEquals($text, $message->getText());
    }

    public function testGetTextWhenItIsNull()
    {
        $message = new Message();

        $this->assertNull($message->getText());
    }

    public function testSetGetId()
    {
        $message = new Message();
        $id = '::id::';

        $this->assertSame($message, $message->setId($id));
        $this->assertEquals($id, $message->getId());
    }

    public function testGetIdWhenItIsNull()
    {
        $message = new Message();

        $this->assertNull($message->getId());
    }

    public function testGetCountWhenItIsNull()
    {
        $message = new Message();

        $this->assertNull($message->getCount());
    }

    public function testGetStatusWhenItIsNull()
    {
        $message = new Message();

        $this->assertNull($message->getStatus());
    }

    public function testSetAttributes()
    {
        $id = '::id::';
        $count = $this->getFaker()->numberBetween(1, 1000);
        $status = '::status::';

        $message = new Message();
        $attributes = [
            'id' => $id,
            'count' => $count,
            'status' => $status
        ];

        $safeOnly = false;
        $message->setAttributes($attributes, $safeOnly);

        $this->assertSame($id, $message->getId());
        $this->assertSame($count, $message->getCount());
        $this->assertSame($status, $message->getStatus());
    }

    // phone validation

    /**
     * @dataProvider scenariosProvider
     * @param string $scenario
     */
    public function testValidatePhoneWhenItIsEmpty(string $scenario)
    {
        $message = new Message();
        $message->scenario = $scenario;

        $this->assertFalse($message->validate('phone'));
        $this->assertEquals('Phone cannot be blank.', $message->getFirstError('phone'));
    }

    public function scenariosProvider()
    {
        return [
            [Message::SCENARIO_SEND],
            [Message::SCENARIO_GET_STATUS],
        ];
    }

    /**
     * @dataProvider wrongPhoneProvider
     * @param $phone
     * @param string $errorMessage
     */
    public function testValidatePhoneWhenOneWrongPhoneAndSendScenario($phone, string $errorMessage)
    {
        $message = new Message();
        $message->scenario = Message::SCENARIO_SEND;
        $message->phone = $phone;

        $this->assertFalse($message->validate('phone'));
        $this->assertEquals($errorMessage, $message->getFirstError('phone'));
    }

    /**
     * @dataProvider wrongPhoneProvider
     * @param $phone
     * @param string $errorMessage
     */
    public function testValidatePhoneWhenOneWrongPhoneAndGetStatusScenario($phone, string $errorMessage)
    {
        $message = new Message();
        $message->scenario = Message::SCENARIO_GET_STATUS;
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
    public function testValidatePhoneWhenOneCorrectPhoneAndSendScenario($phone)
    {
        $message = new Message();
        $message->scenario = Message::SCENARIO_SEND;
        $message->phone = $phone;

        $this->assertTrue($message->validate('phone'));
    }

    /**
     * @dataProvider correctPhoneProvider
     * @param $phone
     */
    public function testValidatePhoneWhenOneCorrectPhoneAndGetStatusScenario($phone)
    {
        $message = new Message();
        $message->scenario = Message::SCENARIO_GET_STATUS;
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

    // text validation

    public function testValidateTextWhenItIsEmptyAndSendScenario()
    {
        $message = new Message();
        $message->scenario = Message::SCENARIO_SEND;

        $this->assertFalse($message->validate('text'));
        $this->assertEquals('Text cannot be blank.', $message->getFirstError('text'));
    }

    public function testValidateTextWhenItIsEmptyAndGetStatusScenario()
    {
        $message = new Message();
        $message->scenario = Message::SCENARIO_GET_STATUS;

        $this->assertTrue($message->validate('text'));
    }

    // id validation

    public function testValidateIdWhenItIsEmptyAndSendScenario()
    {
        $message = new Message();
        $message->scenario = Message::SCENARIO_SEND;

        $this->assertTrue($message->validate('id'));
    }

    public function testValidateIdWhenItIsEmptyAndGetStatusScenario()
    {
        $message = new Message();
        $message->scenario = Message::SCENARIO_GET_STATUS;

        $this->assertFalse($message->validate('id'));
        $this->assertEquals('Id cannot be blank.', $message->getFirstError('id'));
    }

    private function getFaker()
    {
        return Faker\Factory::create();
    }
}
