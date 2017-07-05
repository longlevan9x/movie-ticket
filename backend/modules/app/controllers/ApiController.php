<?php

namespace backend\modules\app\controllers;

use backend\controllers\BaseApiController;
use common\components\FHtml;
use backend\modules\ecommerce\models\Product;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class ApiController extends BaseApiController
{
    public function actions()
    {
        return array_merge([

        ], parent::actions());
    }
}
