<?php
define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

\Yii::setAlias('@smscunit', __DIR__);
\Yii::setAlias('@runtime', __DIR__);
