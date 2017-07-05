<?php

namespace backend\modules\api\controllers;

use backend\controllers\ApiController;

/**
 * Default controller for the `api` module
 */
class TransactionController extends ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'index' => ['class' => 'backend\modules\app\actions\TransactionAction', 'checkAccess' => [$this, 'checkAccess']],
            'list' => ['class' => 'backend\modules\app\actions\TransactionListAction', 'checkAccess' => [$this, 'checkAccess']],
            'accounting' => ['class' => 'backend\modules\app\actions\AccountingAction', 'checkAccess' => [$this, 'checkAccess']],
            'delete' => ['class' => 'backend\modules\app\actions\TransactionDeleteAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }

}
