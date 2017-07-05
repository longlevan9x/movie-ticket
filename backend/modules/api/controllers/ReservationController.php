<?php

namespace backend\modules\api\controllers;

use backend\controllers\ApiController;

/**
 * Default controller for the `api` module
 */
class ReservationController extends ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'index' => ['class' => 'backend\modules\ecommerce\actions\ReservationAction', 'checkAccess' => [$this, 'checkAccess']],
            'list' => ['class' => 'backend\modules\ecommerce\actions\ReservationListAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }

}
