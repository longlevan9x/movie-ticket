<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use backend\modules\app\models\AppUserTokenAPI;
use common\components\FHtml;
use common\components\Response;
use Yii;

/* @var $check AppUserAPI*/

class ProfileAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized())!== true)
            return $re;

        $destination_id = FHtml::getRequestParam('user_id', '');

        $user_id = $this->user_id;

        //Yii::$app->response->statusCode = 400;

        if(strlen($destination_id)==0)
        {
            $check = AppUserAPI::findOne($user_id);
        }else{
            $check = AppUserAPI::findOne($destination_id);
        }


        if (isset($check)) {

            $vehicle_data = $check->vehicle;
//            if(isset($check->vehicle)){
//                $vehicle_data->image = FHtml::getFileURL($check->vehicle->image, TRANSPORT_VEHICLE_DIR, BACKEND, \Globals::NO_IMAGE);
//                $vehicle_data->permit = FHtml::getFileURL($check->vehicle->permit, TRANSPORT_VEHICLE_DIR, BACKEND, \Globals::NO_IMAGE);
//                $vehicle_data->insurance = FHtml::getFileURL($check->vehicle->insurance, TRANSPORT_VEHICLE_DIR, BACKEND, \Globals::NO_IMAGE);
//            }


            $driver_data = $check->driver;
//            if(isset($check->driver)){
//                $driver_data->driver_license = FHtml::getFileURL($check->driver->driver_license, TRANSPORT_DRIVER_DIR, BACKEND, \Globals::NO_IMAGE);
//            }

            $pro_data = $check->pro;

            $data = $check;
            //$data->avatar = FHtml::getFileURL($check->avatar, APP_USER_DIR, BACKEND, \Globals::NO_IMAGE);
            $data->pro_data = $pro_data;
            $data->driver_data = $driver_data;
            $data->vehicle_data = $vehicle_data;
            // Yii::$app->response->statusCode = 200;
            return Response::getOutputForAPI($data, \Globals::SUCCESS, 'OK', ['code'=> 200]);
        } else {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::USER_NOT_FOUND, ['code'=> 221]);
        }
    }

}
