<?php

namespace backend\modules\api;
use yii\base\Module;

/**
 * api module definition class
 */
class Api extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\api\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
