<?php

namespace backend\models;

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
 * This is the customized model class for table "user".
 */
class User extends UserBase //\yii\db\ActiveRecord
{
    const LOOKUP = [        'role' => [
		['id' => User::ROLE_ADMIN, 'name' => 'ADMIN'],
 ],
        'status' => [
		['id' => User::STATUS_DISABLED, 'name' => 'DISABLED'],
 	['id' => User::STATUS_ACTIVE, 'name' => 'ACTIVE'],
 ],
];

    const COLUMNS_UPLOAD = ['image',];

    public $order_by = '';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    public static function getLookupArray($column) {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'code', 'name', 'username', 'image', 'overview', 'content', 'auth_key', 'password_hash', 'password_reset_token', 'birth_date', 'birth_place', 'gender', 'identity_card', 'email', 'phone', 'skype', 'address', 'city', 'organization', 'department', 'position', 'start_date', 'end_date', 'role', 'type', 'status', 'is_online', 'last_login', 'last_logout', 'created_at', 'updated_at', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['content'], 'string'],
            [['birth_date', 'start_date', 'end_date', 'last_login', 'last_logout'], 'safe'],
            [['role', 'status', 'is_online', 'created_at', 'updated_at'], 'integer'],
            [['code', 'name', 'username', 'password_hash', 'password_reset_token', 'birth_place', 'identity_card', 'email', 'phone', 'skype'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 300],
            [['overview', 'address'], 'string', 'max' => 2000],
            [['auth_key'], 'string', 'max' => 32],
            [['gender', 'city', 'organization', 'department', 'position', 'type', 'application_id'], 'string', 'max' => 100],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }




    public function prepareCustomFields() {
        parent::prepareCustomFields();

    }


    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
    }
}
