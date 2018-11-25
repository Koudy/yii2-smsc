<?php

namespace koudy\yii2\smsc;

use koudy\yii2\smsc\interfaces\Response;
use yii\base\Model;

class GetStatusResponse extends Model implements Response
{
    /**
     * @var string
     */
    public $status;

    /**
     * @inheritdoc
     */
    public function getData(): array
    {
        return [
            'status' => $this->status
        ];
    }
}
