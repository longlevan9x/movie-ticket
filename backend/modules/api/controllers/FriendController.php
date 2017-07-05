<?php

namespace backend\modules\api\controllers;

use backend\controllers\ApiController;

/**
 * Default controller for the `api` module
 */
class FriendController extends ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'index' => ['class' => 'backend\modules\app\actions\FriendAction', 'checkAccess' => [$this, 'checkAccess']],
            'list' => ['class' => 'backend\modules\app\actions\FriendListAction', 'checkAccess' => [$this, 'checkAccess']],
           ];
    }
}
