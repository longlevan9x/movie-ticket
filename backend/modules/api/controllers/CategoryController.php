<?php

namespace backend\modules\api\controllers;

use backend\controllers\ApiController;

/**
 * Default controller for the `api` module
 */
class CategoryController extends ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'list' => ['class' => 'backend\actions\CategoryAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }

}
