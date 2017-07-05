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
 * This is the customized model class for table "settings".
 */
class Settings extends SettingsBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = '';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'metaKey', 'metaValue', 'group', 'editor', 'lookup', 'is_system', 'application_id', ],
        'all' => ['id', 'metaKey', 'metaValue', 'group', 'editor', 'lookup', 'is_system', 'application_id',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => [  'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'metaKey', 'metaValue', 'group', 'editor', 'lookup', 'is_system', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['metaKey'], 'required'],
            [['is_system'], 'integer'],
            [['metaKey', 'metaValue', 'group', 'editor', 'lookup'], 'string', 'max' => 255],
            [['application_id'], 'string', 'max' => 100],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('Settings', 'ID'),
                    'metaKey' => FHtml::t('Settings', 'Meta Key'),
                    'metaValue' => FHtml::t('Settings', 'Meta Value'),
                    'group' => FHtml::t('Settings', 'Group'),
                    'editor' => FHtml::t('Settings', 'Editor'),
                    'lookup' => FHtml::t('Settings', 'Lookup'),
                    'is_system' => FHtml::t('Settings', 'Is System'),
                    'application_id' => FHtml::t('Settings', 'Application ID'),
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
        $i18n->translations['Settings*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'Settings' => 'Settings.php',
            ],
        ];
    }

    public function toViewModel() {
    $model = new ViewModel();
            FHtml::setFieldValue($model, ['id'], $this->id);
            FHtml::setFieldValue($model, ['metaKey'], $this->metaKey);
            FHtml::setFieldValue($model, ['metaValue'], $this->metaValue);
            FHtml::setFieldValue($model, ['group'], $this->group);
            FHtml::setFieldValue($model, ['editor'], $this->editor);
            FHtml::setFieldValue($model, ['lookup'], $this->lookup);
            FHtml::setFieldValue($model, ['is_system'], $this->is_system);
            FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }

    public static function getDb()
    {
        return FHtml::currentDb();
    }
}
