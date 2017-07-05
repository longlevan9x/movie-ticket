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
 * This is the customized model class for table "object_file".
 */
class ObjectFile extends ObjectFileBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = ['file','file_type','file_size','file_duration',];

    public $order_by = 'sort_order asc,is_active desc,created_date desc,';
    public $file_upload;

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'object_id', 'object_type', 'file', 'title', 'description', 'file_type', 'file_size', 'file_duration', 'is_active', 'sort_order', 'created_date', 'created_user', 'application_id', ],
        'all' => ['id', 'object_id', 'object_type', 'file', 'title', 'description', 'file_type', 'file_size', 'file_duration', 'is_active', 'sort_order', 'created_date', 'created_user', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => [  'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'object_id', 'object_type', 'file', 'title', 'description', 'file_type', 'file_size', 'file_duration', 'is_active', 'sort_order', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['object_id', 'object_type', 'title'], 'required'],
            [['object_id', 'is_active', 'sort_order'], 'integer'],
            [['created_date'], 'safe'],
            [['object_type', 'file_type', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['file'], 'string', 'max' => 555],
            [['title', 'file_size', 'file_duration'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('ObjectFile', 'ID'),
                    'object_id' => FHtml::t('ObjectFile', 'Object ID'),
                    'object_type' => FHtml::t('ObjectFile', 'Object Type'),
                    'file' => FHtml::t('ObjectFile', 'File'),
                    'title' => FHtml::t('ObjectFile', 'Title'),
                    'description' => FHtml::t('ObjectFile', 'Description'),
                    'file_type' => FHtml::t('ObjectFile', 'File Type'),
                    'file_size' => FHtml::t('ObjectFile', 'File Size'),
                    'file_duration' => FHtml::t('ObjectFile', 'File Duration'),
                    'is_active' => FHtml::t('ObjectFile', 'Is Active'),
                    'sort_order' => FHtml::t('ObjectFile', 'Sort Order'),
                    'created_date' => FHtml::t('ObjectFile', 'Created Date'),
                    'created_user' => FHtml::t('ObjectFile', 'Created User'),
                    'application_id' => FHtml::t('ObjectFile', 'Application ID'),
                ];
    }




    public function prepareCustomFields() {
        parent::prepareCustomFields();

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
        $i18n->translations['ObjectFile*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectFile' => 'ObjectFile.php',
            ],
        ];
    }

    public function toViewModel() {
    $model = new ViewModel();
            FHtml::setFieldValue($model, ['id'], $this->id);
            FHtml::setFieldValue($model, ['object_id'], $this->object_id);
            FHtml::setFieldValue($model, ['object_type'], $this->object_type);
            FHtml::setFieldValue($model, ['file'], $this->file);
            FHtml::setFieldValue($model, ['title'], $this->title);
            FHtml::setFieldValue($model, ['description'], $this->description);
            FHtml::setFieldValue($model, ['file_type'], $this->file_type);
            FHtml::setFieldValue($model, ['file_size'], $this->file_size);
            FHtml::setFieldValue($model, ['file_duration'], $this->file_duration);
            FHtml::setFieldValue($model, ['is_active'], $this->is_active);
            FHtml::setFieldValue($model, ['sort_order'], $this->sort_order);
            FHtml::setFieldValue($model, ['created_date'], $this->created_date);
            FHtml::setFieldValue($model, ['created_user'], $this->created_user);
            FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
