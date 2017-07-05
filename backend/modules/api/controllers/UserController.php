<?php

namespace backend\modules\api\controllers;

use backend\controllers\ApiController;

/**
 * Default controller for the `api` module
 */
class UserController extends ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'login' => ['class' => 'backend\modules\app\actions\LoginAction', 'checkAccess' => [$this, 'checkAccess']],
            'register' => ['class' => 'backend\modules\app\actions\RegisterAction', 'checkAccess' => [$this, 'checkAccess']],
            'profile' => ['class' => 'backend\modules\app\actions\ProfileAction', 'checkAccess' => [$this, 'checkAccess']],
            'logout' => ['class' => 'backend\modules\app\actions\LogoutAction', 'checkAccess' => [$this, 'checkAccess']],
            'forget-password' => ['class' => 'backend\modules\app\actions\ForgetPasswordAction', 'checkAccess' => [$this, 'checkAccess']],
            'update-profile' => ['class' => 'backend\modules\app\actions\UpdateProfileAction', 'checkAccess' => [$this, 'checkAccess']],
            'update-pro-profile' => ['class' => 'backend\modules\app\actions\UpdateProProfileAction', 'checkAccess' => [$this, 'checkAccess']],
            'change-password' => ['class' => 'backend\modules\app\actions\ChangePasswordAction', 'checkAccess' => [$this, 'checkAccess']],
            'setting' => ['class' => 'backend\modules\app\actions\SettingAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }
}
