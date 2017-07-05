<?php

namespace backend\modules\api\controllers;

use backend\controllers\ApiController;

/**
 * Default controller for the `api` module
 */
class DealController extends ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'index' => ['class' => 'backend\modules\product\actions\DealAction', 'checkAccess' => [$this, 'checkAccess']],
            'list' => ['class' => 'backend\modules\product\actions\DealListAction', 'checkAccess' => [$this, 'checkAccess']],
            'detail' => ['class' => 'backend\modules\product\actions\DealDetailAction', 'checkAccess' => [$this, 'checkAccess']],
            'switch' => ['class' => 'backend\modules\product\actions\DealSwitchAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }

}
