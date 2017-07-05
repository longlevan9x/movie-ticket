<?php

namespace backend\modules\app\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "app_user".
 */
class AppUser extends AppUserBase //\yii\db\ActiveRecord
{

    public $pro_data;
    public $driver_data;
    public $vehicle_data;
    public $is_secured;

    const LOOKUP = [        'status' => [['id' => AppUser::STATUS_PENDING, 'name' => 'PENDING'], ['id' => AppUser::STATUS_BANNED, 'name' => 'BANNED'], ['id' => AppUser::STATUS_REJECTED, 'name' => 'REJECTED'], ['id' => AppUser::STATUS_NORMAL, 'name' => 'NORMAL'], ['id' => AppUser::STATUS_PRO, 'name' => 'PRO'], ['id' => AppUser::STATUS_VIP, 'name' => 'VIP'], ],
        'role' => [['id' => AppUser::ROLE_ADMIN, 'name' => 'ADMIN'], ],
];

    const COLUMNS_UPLOAD = ['avatar',];

    public $order_by = 'is_active desc,created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'avatar', 'name', 'username', 'email', 'password', 'auth_key', 'password_hash', 'password_reset_token', 'description', 'content', 'gender', 'dob', 'phone', 'weight', 'height', 'address', 'country', 'state', 'city', 'balance', 'point', 'card_number', 'card_cvv', 'card_exp', 'lat', 'long', 'rate', 'rate_count', 'is_online', 'is_active', 'type', 'status', 'role', 'provider_id', 'created_date', 'modified_date', ],
        'all' => ['id', 'avatar', 'name', 'username', 'email', 'password', 'auth_key', 'password_hash', 'password_reset_token', 'description', 'content', 'gender', 'dob', 'phone', 'weight', 'height', 'address', 'country', 'state', 'city', 'balance', 'point', 'card_number', 'card_cvv', 'card_exp', 'lat', 'long', 'rate', 'rate_count', 'is_online', 'is_active', 'type', 'status', 'role', 'provider_id', 'created_date', 'modified_date',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['provider',   'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'avatar', 'name', 'username', 'email', 'password', 'auth_key', 'password_hash', 'password_reset_token', 'description', 'content', 'gender', 'dob', 'phone', 'weight', 'height', 'address', 'country', 'state', 'city', 'balance', 'point', 'card_number', 'card_cvv', 'card_exp', 'lat', 'long', 'rate', 'rate_count', 'is_online', 'is_active', 'type', 'status', 'role', 'provider_id', 'created_date', 'modified_date'], 'filter', 'filter' => 'trim'],
                
            [['name', 'username', 'email', 'password'], 'required'],
            [['content'], 'string'],
            [['balance', 'rate'], 'number'],
            [['point', 'rate_count', 'is_online', 'is_active', 'role'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['avatar', 'name', 'username', 'email', 'password', 'password_hash', 'password_reset_token', 'dob', 'weight', 'height', 'address', 'card_number', 'card_cvv', 'card_exp', 'lat', 'long'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 2000],
            [['gender', 'country', 'state', 'city', 'type', 'status', 'provider_id'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 25],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('AppUser', 'ID'),
                    'avatar' => FHtml::t('AppUser', 'Avatar'),
                    'name' => FHtml::t('AppUser', 'Name'),
                    'username' => FHtml::t('AppUser', 'Username'),
                    'email' => FHtml::t('AppUser', 'Email'),
                    'password' => FHtml::t('AppUser', 'Password'),
                    'auth_key' => FHtml::t('AppUser', 'Auth Key'),
                    'password_hash' => FHtml::t('AppUser', 'Password Hash'),
                    'password_reset_token' => FHtml::t('AppUser', 'Password Reset Token'),
                    'description' => FHtml::t('AppUser', 'Description'),
                    'content' => FHtml::t('AppUser', 'Content'),
                    'gender' => FHtml::t('AppUser', 'Gender'),
                    'dob' => FHtml::t('AppUser', 'Dob'),
                    'phone' => FHtml::t('AppUser', 'Phone'),
                    'weight' => FHtml::t('AppUser', 'Weight'),
                    'height' => FHtml::t('AppUser', 'Height'),
                    'address' => FHtml::t('AppUser', 'Address'),
                    'country' => FHtml::t('AppUser', 'Country'),
                    'state' => FHtml::t('AppUser', 'State'),
                    'city' => FHtml::t('AppUser', 'City'),
                    'balance' => FHtml::t('AppUser', 'Balance'),
                    'point' => FHtml::t('AppUser', 'Point'),
                    'card_number' => FHtml::t('AppUser', 'Card Number'),
                    'card_cvv' => FHtml::t('AppUser', 'Card Cvv'),
                    'card_exp' => FHtml::t('AppUser', 'Card Exp'),
                    'lat' => FHtml::t('AppUser', 'Lat'),
                    'long' => FHtml::t('AppUser', 'Long'),
                    'rate' => FHtml::t('AppUser', 'Rate'),
                    'rate_count' => FHtml::t('AppUser', 'Rate Count'),
                    'is_online' => FHtml::t('AppUser', 'Is Online'),
                    'is_active' => FHtml::t('AppUser', 'Is Active'),
                    'type' => FHtml::t('AppUser', 'Type'),
                    'status' => FHtml::t('AppUser', 'Status'),
                    'role' => FHtml::t('AppUser', 'Role'),
                    'provider_id' => FHtml::t('AppUser', 'Provider ID'),
                    'created_date' => FHtml::t('AppUser', 'Created Date'),
                    'modified_date' => FHtml::t('AppUser', 'Modified Date'),
                ];
    }



    // Lookup Object: provider\n
    public $provider;
    public function getProvider() {
        if (!isset($this->provider))
        $this->provider = FHtml::getModel('provider', '', $this->provider_id, '', false);

        return $this->provider;
    }
    public function setProvider($value) {
        $this->provider = $value;
    }


    public function prepareCustomFields() {
        parent::prepareCustomFields();

        $this->provider = self::getProvider();
    }

    public function fields()
    {
        $fields = parent::fields();

        $columns = self::COLUMNS;
        if (is_string($this->columnsMode) && !empty($this->columnsMode) && key_exists($this->columnsMode, $columns)) {
            $fields1 = $columns[$this->columnsMode];
            if (!empty($fields1))
            $fields = $fields1;
        } else if (is_array($this->columnsMode))
            return $this->columnsMode;

        if (key_exists('+', $columns) && !empty($columns['+'])) {
            $fields = array_merge($fields, $columns['+']);
        }
        //unset($fields['xxx'], $fields['yyy'], $fields['zzz']);

        return $fields;
    }

    public static function getLookupArray($column) {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
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

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'is_active' => self::STATUS_ACTIVE]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'is_active' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotFoundHttpException();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return $this->role;
    }

    public static function isAdmin($role)
    {
        return $role == self::ROLE_ADMIN;
    }

    public static function isModerator($role)
    {
        return $role == self::ROLE_MODERATOR;
    }

    public static function isNormalUser($role)
    {
        return $role == self::ROLE_USER;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @inheritdoc
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public static function findUser($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }


    //connections

    public function getDevice()
    {
        return $this->hasOne(AppUserDeviceAPI::className(), ['user_id' => 'id']);
    }

    public function getPro()
    {
        return $this->hasOne(AppUserProAPI::className(), ['user_id' => 'id']);
    }


    public function getLoginToken()
    {
        return $this->hasOne(AppUserTokenAPI::className(), ['user_id' => 'id']);
    }

//    public function getDriver()
//    {
//        return $this->hasOne(TransportDriverAPI::className(), ['user_id' => 'id']);
//    }
//
//    public function getVehicle()
//    {
//        return $this->hasOne(TransportVehicleAPI::className(), ['user_id' => 'id']);
//    }
//
//    public function getToMeReviews()
//    {
//        return $this->hasMany(AppUserReviewAPI::className(), ['destination_id' => 'id']);
//    }
//
//    public function getByMeReviews()
//    {
//        return $this->hasMany(AppUserReviewAPI::className(), ['author_id' => 'id'])
//            ->andOnCondition(['or',
//                ['destination_role' => \Globals::ROLE_BUYER],
//                ['destination_role' => \Globals::ROLE_PASSENGER]
//            ]);
//    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['AppUser*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'AppUser' => 'AppUser.php',
            ],
        ];
    }

    public function toViewModel() {
    $model = new ViewModel();
            FHtml::setFieldValue($model, ['id'], $this->id);
            FHtml::setFieldValue($model, ['avatar'], $this->avatar);
            FHtml::setFieldValue($model, ['name'], $this->name);
            FHtml::setFieldValue($model, ['username'], $this->username);
            FHtml::setFieldValue($model, ['email'], $this->email);
            FHtml::setFieldValue($model, ['password'], $this->password);
            FHtml::setFieldValue($model, ['auth_key'], $this->auth_key);
            FHtml::setFieldValue($model, ['password_hash'], $this->password_hash);
            FHtml::setFieldValue($model, ['password_reset_token'], $this->password_reset_token);
            FHtml::setFieldValue($model, ['description'], $this->description);
            FHtml::setFieldValue($model, ['content'], $this->content);
            FHtml::setFieldValue($model, ['gender'], $this->gender);
            FHtml::setFieldValue($model, ['dob'], $this->dob);
            FHtml::setFieldValue($model, ['phone'], $this->phone);
            FHtml::setFieldValue($model, ['weight'], $this->weight);
            FHtml::setFieldValue($model, ['height'], $this->height);
            FHtml::setFieldValue($model, ['address'], $this->address);
            FHtml::setFieldValue($model, ['country'], $this->country);
            FHtml::setFieldValue($model, ['state'], $this->state);
            FHtml::setFieldValue($model, ['city'], $this->city);
            FHtml::setFieldValue($model, ['balance'], $this->balance);
            FHtml::setFieldValue($model, ['point'], $this->point);
            FHtml::setFieldValue($model, ['card_number'], $this->card_number);
            FHtml::setFieldValue($model, ['card_cvv'], $this->card_cvv);
            FHtml::setFieldValue($model, ['card_exp'], $this->card_exp);
            FHtml::setFieldValue($model, ['lat'], $this->lat);
            FHtml::setFieldValue($model, ['long'], $this->long);
            FHtml::setFieldValue($model, ['rate'], $this->rate);
            FHtml::setFieldValue($model, ['rate_count'], $this->rate_count);
            FHtml::setFieldValue($model, ['is_online'], $this->is_online);
            FHtml::setFieldValue($model, ['is_active'], $this->is_active);
            FHtml::setFieldValue($model, ['type'], $this->type);
            FHtml::setFieldValue($model, ['status'], $this->status);
            FHtml::setFieldValue($model, ['role'], $this->role);
            FHtml::setFieldValue($model, ['provider_id'], $this->provider_id);
            FHtml::setFieldValue($model, ['created_date'], $this->created_date);
            FHtml::setFieldValue($model, ['modified_date'], $this->modified_date);
        return $model;
    }
}
