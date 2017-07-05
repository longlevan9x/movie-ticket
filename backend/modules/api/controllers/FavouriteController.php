<?php

namespace backend\modules\api\controllers;

use backend\controllers\ApiController;

/**
 * Default controller for the `api` module
 */
class FavouriteController extends ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'index' => ['class' => 'backend\modules\app\actions\FavouriteAction', 'checkAccess' => [$this, 'checkAccess']],
      ];
    }

}
