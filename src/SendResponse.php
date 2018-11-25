<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Response;
use yii\base\Model;

class SendResponse extends Model implements Response
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var int
     */
    public $cnt;

    /**
     * @var array
     */
    public $phones;

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        return [
            'id' => $this->id,
            'count' => $this->cnt,
            'status' => $this->phones[0]['status']
        ];
    }
}
