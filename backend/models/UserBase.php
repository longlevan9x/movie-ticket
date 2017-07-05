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
 * This is the model class for table "user".
 *

 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $username
 * @property string $image
 * @property string $overview
 * @property string $content
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $birth_date
 * @property string $birth_place
 * @property string $gender
 * @property string $identity_card
 * @property string $email
 * @property string $phone
 * @property string $skype
 * @property string $address
 * @property string $country
 * @property string $city
 * @property string $organization
 * @property string $department
 * @property string $position
 * @property string $start_date
 * @property string $end_date
 * @property integer $role
 * @property string $type
 * @property integer $status
 * @property integer $is_online
 * @property string $last_login
 * @property string $last_logout
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $application_id
 */
class UserBase extends \common\models\User //\yii\db\ActiveRecord
{
    const ROLE_ADMIN = 'ADMIN';
    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 10;

    /**
    * @inheritdoc
    */
    public $tableName = 'user';

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => 'ID',
                    'code' => 'Code',
                    'name' => 'Name',
                    'username' => 'Username',
                    'image' => 'Image',
                    'overview' => 'Overview',
                    'content' => 'Content',
                    'auth_key' => 'Auth Key',
                    'password_hash' => 'Password Hash',
                    'password_reset_token' => 'Password Reset Token',
                    'birth_date' => 'Birth Date',
                    'birth_place' => 'Birth Place',
                    'gender' => 'Gender',
                    'identity_card' => 'Identity Card',
                    'email' => 'Email',
                    'phone' => 'Phone',
                    'skype' => 'Skype',
                    'address' => 'Address',
                    'country' => 'Country',
                    'city' => 'City',
                    'organization' => 'Organization',
                    'department' => 'Department',
                    'position' => 'Position',
                    'start_date' => 'Start Date',
                    'end_date' => 'End Date',
                    'role' => 'Role',
                    'type' => 'Type',
                    'status' => 'Status',
                    'is_online' => 'Is Online',
                    'last_login' => 'Last Login',
                    'last_logout' => 'Last Logout',
                    'created_at' => 'Created At',
                    'updated_at' => 'Updated At',
                    'application_id' => 'Application ID',
                ];
    }

    public static function tableSchema()
    {
        return FHtml::getTableSchema(self::tableName());
    }

    public static function Columns()
    {
        return self::tableSchema()->columns;
    }

    public static function ColumnsArray()
    {
        return ArrayHelper::getColumn(self::tableSchema()->columns, 'name');
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['User*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'User' => 'User.php',
            ],
        ];
    }



}
