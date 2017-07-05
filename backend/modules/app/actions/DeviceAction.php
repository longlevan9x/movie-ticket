<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserDeviceAPI;
use backend\modules\app\models\AppUserTokenAPI;
use common\components\Response;
use Yii;

class DeviceAction extends BaseAction
{
    public function run()
    {
        $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        $ime = isset($_REQUEST['ime']) ? $_REQUEST['ime'] : '';
        $gcm_id = isset($_REQUEST['gcm_id']) ? $_REQUEST['gcm_id'] : '';
        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

        if(strlen($token) != 0)
        {
            $login_token = AppUserTokenAPI::find()->where('token = "'.$token.'"')->one();

            if(isset($login_token) && isset ($login_token->user)){
                $user_id = $login_token->user->id;
            }
            else{
                return Response::getOutputForAPI('', \Globals::ERROR, \Globals::TOKEN_MISMATCH, ['code'=> 205]);
            }
        }else
        {
            $user_id = NULL;
        }

        if (strlen($ime) == 0 || strlen($gcm_id) == 0)
        {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::MISSING_PARAMS, ['code'=> 202]);
        }


        $check = AppUserDeviceAPI::find()->where("ime = '" . $ime . "'")->one();
        /* @var $check AppUserDeviceAPI*/
        if (isset($check)) {

            if(strlen($status) != 0)
            {
                $check->status = $status;
            }
            $check->gcm_id = $gcm_id;
            $check->user_id = $user_id;

            if ($check->save()) {
                return Response::getOutputForAPI($check, \Globals::SUCCESS, 'OK', ['code'=> 200]);

            } else {
                return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code'=> 201]);

            }
        } else {
            $new_device = new AppUserDeviceAPI();
            $new_device->user_id = $user_id;
            $new_device->status = $status;
            $new_device->ime = $ime;
            $new_device->type = $type;
            $new_device->status = \Globals::STATE_ACTIVE;
            $new_device->gcm_id = $gcm_id;
            if ($new_device->save()) {
                return Response::getOutputForAPI($new_device, \Globals::SUCCESS, 'OK', ['code'=> 200]);
            } else {
                return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code'=> 201]);
            }
        }
    }

}
