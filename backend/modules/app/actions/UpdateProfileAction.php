<?php

namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use backend\modules\app\models\AppUserTokenAPI;
use common\components\FHtml;
use common\components\Response;
use Imagine\Image\Box;
use Yii;
use yii\imagine\Image;

class UpdateProfileAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized())!== true)
            return $re;

        $user_id = $this->user_id;

        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
        $description = isset($_REQUEST['description']) ? $_REQUEST['description'] : '';
        $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
        $phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
        $dob = isset($_REQUEST['dob']) ? $_REQUEST['dob'] : '';
        $address = isset($_REQUEST['address']) ? $_REQUEST['address'] : '';
        $qb_id = isset($_REQUEST['qb_id']) ? $_REQUEST['qb_id'] : '';

        $file_path = Yii::getAlias('@' . UPLOAD_DIR) . '/' . APP_USER_DIR . '/';

        $check = AppUserAPI::findOne($user_id);
        if (isset($check)) {

            $oldAvatar = $check->avatar;
            $upload = false;
            $now = time();
            $imageName = '';

            if(isset($_FILES['avatar'])){
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $imageName = $now . 'avatar.'. $ext;

                $image_path = $file_path . $imageName;
                $upload = move_uploaded_file($_FILES['avatar']['tmp_name'], $image_path);

                if($upload){
                    $check->avatar = $imageName;
                    Image::getImagine()->open($image_path)
                        ->thumbnail(new Box(100, 100))
                        ->save($file_path . 'thumb' . $imageName , ['quality' => 100]);
                }
            }
            if (strlen($password) != 0) {
                $check->setPassword($password);
            }
            if (strlen($name) != 0) {
                $check->name = $name;
            }
            if (strlen($description) != 0) {
                $check->description = $description;
            }
            if (strlen($gender) != 0) {
                $check->gender = $gender;
            }
            if (strlen($phone) != 0) {
                $check->phone = $phone;
            }
            if (strlen($dob) != 0) {
                $check->dob = $dob;
            }
            if (strlen($address) != 0) {
                $check->address = $address;
            }
            if (strlen($qb_id) != 0) {
                $check->qb_id = $qb_id;
            }

            $check->modified_date = date('Y-m-d H:i:s', $now);

            if ($check->save()) {

                if($upload){
                    if (is_file($file_path . '/' . $oldAvatar)) {
                        unlink($file_path . '/' . $oldAvatar);
                    }
                    if (is_file($file_path . '/thumb' . $oldAvatar)) {
                        unlink($file_path . '/thumb' . $oldAvatar);
                    }
                }

                $check = AppUserAPI::findOne($user_id);

                $vehicle_data = $check->vehicle;
                $driver_data = $check->driver;
                $pro_data = $check->pro;

                $data = $check;
                $data->pro_data = $pro_data;
                $data->driver_data = $driver_data;
                $data->vehicle_data = $vehicle_data;

                return Response::getOutputForAPI($data, \Globals::SUCCESS, 'OK', ['code'=> 200]);

            } else {
                if(isset($_FILES['avatar'])) {
                    if ($upload && strlen($imageName)!=0) {
                        if (is_file($file_path . '/' . $imageName)) {
                            unlink($file_path . '/' . $imageName);
                        }
                        if (is_file($file_path . '/thumb' . $imageName)) {
                            unlink($file_path . '/thumb' . $imageName);
                        }
                    }
                }

                return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code'=> 201]);
            }
        } else {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::USER_NOT_FOUND, ['code'=> 221]);
        }
    }
}
