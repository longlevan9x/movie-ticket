<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "app_user".
 */
class AppUserAPI extends AppUser
{
    public function fields()
    {
        //$fields = parent::fields();
        $fields = [
            'id',
            'qb_id',
            'avatar',
            'name',
            'username',
            'email',
            'description',
            'balance',
            'gender',
            'phone',
            'dob',
            'address',
            'lat',
            'long',
            'is_active',
            'status',
            'rate',
            'rate_count',
            'created_date',
            'modified_date'
        ];

        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            $avatar_link =  $this->avatar;
        }else{
            if(!isset($this->avatar) || strlen($this->avatar) == 0){
                $avatar_link = "";
            }else{
                $avatar_link = Yii::$app->urlManager->createAbsoluteUrl(['api/file', 'f'=> $this->avatar, 'd' => APP_USER_DIR, 's'=>'thumb']);
            }
        }

        $this->avatar = $avatar_link;
        $this->is_secured = isset($this->password) && strlen($this->password)!= 0 ? 1 : 0;

        $fields = array_merge($fields, ['is_secured','pro_data', 'driver_data', 'vehicle_data']);
        return $fields;

    }

    public function rules()
    {
        return []; // API does not require rules
    }
}
