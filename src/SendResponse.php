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
        $data = [
            'id' => $this->id,
            'count' => $this->cnt
        ];

        if (array_key_exists('status', $this->phones[0])) {
            $data['status'] = $this->phones[0]['status'];
        }

        return $data;
    }
}
