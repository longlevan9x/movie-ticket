<?php

namespace backend\modules\api\controllers;

use backend\controllers\ApiController;
use backend\modules\api\Api;
use common\components\FHtml;

/**
 * Default controller for the `api` module
 */
class UtilityController extends ApiController
{
    public $enableCsrfValidation = false;


    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'update-location' => ['class' => 'backend\actions\UpdateLocationAction', 'checkAccess' => [$this, 'checkAccess']],
            'setting' => ['class' => 'backend\actions\ShowAppSettingAction', 'checkAccess' => [$this, 'checkAccess']],
            'sendEmail' => ['class' => 'backend\actions\SendEmailAction', 'checkAccess' => [$this, 'checkAccess']],
            'test' => ['class' => 'backend\actions\TestAction', 'checkAccess' => [$this, 'checkAccess']],
            'pushNotification' => ['class' => 'backend\actions\PushNotificationAction', 'checkAccess' => [$this, 'checkAccess']],
            'error-code' => ['class' => 'backend\actions\ErrorCodeAction', 'checkAccess' => [$this, 'checkAccess']],
            'cron-deal' => ['class' => 'backend\actions\CronDealAction', 'checkAccess' => [$this, 'checkAccess']],
            'cron-driver' => ['class' => 'backend\actions\CronDriverAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }
}
