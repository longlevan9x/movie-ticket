<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use common\components\Response;

class ChangePasswordAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized())!== true)
            return $re;

        $user_id = $this->user_id;

        $current_password = isset($_REQUEST['current_password']) ? $_REQUEST['current_password'] : '';
        $new_password = isset($_REQUEST['new_password']) ? $_REQUEST['new_password'] : '';

        $check = AppUserAPI::findOne($user_id);
        if (isset($check)) {
            if(isset($check->password) && strlen($check->password)!=0){
                    if (!$check->validatePassword($current_password)) {
                        return Response::getOutputForAPI('', \Globals::ERROR, 'Current password mismatch', ['code'=> 225]);
                    }
            }
            $check->setPassword($new_password);
            $check->generateAuthKey();

            if ($check->save()) {
                return Response::getOutputForAPI('', \Globals::SUCCESS, 'OK', ['code'=> 200]);
            } else {
                return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code'=> 201]);
            }
        } else {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::USER_NOT_FOUND, ['code'=> 221]);
        }
    }
}
