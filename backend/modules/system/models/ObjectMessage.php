<?php

namespace backend\modules\system\models;

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
 * This is the customized model class for table "object_message".
 */
class ObjectMessage extends ObjectMessageBase //\yii\db\ActiveRecord
{
    const LOOKUP = [        'status' => [['id' => ObjectMessage::STATUS_PLAN, 'name' => 'Plan'], ['id' => ObjectMessage::STATUS_SENT, 'name' => 'Sent'], ['id' => ObjectMessage::STATUS_RECEIVED, 'name' => 'Received'], ['id' => ObjectMessage::STATUS_READ, 'name' => 'Read'], ],
        'type' => [['id' => ObjectMessage::TYPE_WARNING, 'name' => 'Warning'], ['id' => ObjectMessage::TYPE_BIRTHDAY, 'name' => 'Birthday'], ['id' => ObjectMessage::TYPE_REMIND, 'name' => 'Remind'], ['id' => ObjectMessage::TYPE_PROMOTION, 'name' => 'Promotion'], ],
        'method' => [['id' => ObjectMessage::METHOD_PUSH, 'name' => 'Push'], ['id' => ObjectMessage::METHOD_EMAIL, 'name' => 'Email'], ['id' => ObjectMessage::METHOD_SMS, 'name' => 'SMS'], ],
];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'created_date desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'object_id', 'object_type', 'message', 'status', 'type', 'method', 'sent_date', 'created_date', 'created_user', 'application_id', ],
        'all' => ['id', 'object_id', 'object_type', 'message', 'status', 'type', 'method', 'sent_date', 'created_date', 'created_user', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => ['object',   'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'object_id', 'object_type', 'message', 'status', 'type', 'method', 'sent_date', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['object_id'], 'required'],
            [['sent_date', 'created_date'], 'safe'],
            [['object_id', 'object_type', 'status', 'type', 'method', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['message'], 'string', 'max' => 4000],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('ObjectMessage', 'ID'),
                    'object_id' => FHtml::t('ObjectMessage', 'Object ID'),
                    'object_type' => FHtml::t('ObjectMessage', 'Object Type'),
                    'message' => FHtml::t('ObjectMessage', 'Message'),
                    'status' => FHtml::t('ObjectMessage', 'Status'),
                    'type' => FHtml::t('ObjectMessage', 'Type'),
                    'method' => FHtml::t('ObjectMessage', 'Method'),
                    'sent_date' => FHtml::t('ObjectMessage', 'Sent Date'),
                    'created_date' => FHtml::t('ObjectMessage', 'Created Date'),
                    'created_user' => FHtml::t('ObjectMessage', 'Created User'),
                    'application_id' => FHtml::t('ObjectMessage', 'Application ID'),
                ];
    }



    // Lookup Object: object\n
    public $object;
    public function getObject() {
        if (!isset($this->object))
        $this->object = FHtml::getModel('app_user', '', $this->object_id, '', false);

        return $this->object;
    }
    public function setObject($value) {
        $this->object = $value;
    }


    public function prepareCustomFields() {
        parent::prepareCustomFields();

        $this->object = self::getObject();
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


    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['ObjectMessage*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectMessage' => 'ObjectMessage.php',
            ],
        ];
    }

    public function toViewModel() {
    $model = new ViewModel();
            FHtml::setFieldValue($model, ['id'], $this->id);
            FHtml::setFieldValue($model, ['object_id'], $this->object_id);
            FHtml::setFieldValue($model, ['object_type'], $this->object_type);
            FHtml::setFieldValue($model, ['message'], $this->message);
            FHtml::setFieldValue($model, ['status'], $this->status);
            FHtml::setFieldValue($model, ['type'], $this->type);
            FHtml::setFieldValue($model, ['method'], $this->method);
            FHtml::setFieldValue($model, ['sent_date'], $this->sent_date);
            FHtml::setFieldValue($model, ['created_date'], $this->created_date);
            FHtml::setFieldValue($model, ['created_user'], $this->created_user);
            FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
