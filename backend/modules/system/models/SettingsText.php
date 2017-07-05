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
 * This is the customized model class for table "settings_text".
 */
class SettingsText extends SettingsTextBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = '';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];
    const COLUMNS = [
        'api' => ['id', 'group', 'name', 'description', 'description_en', 'description_es', 'description_pt', 'description_de', 'description_fr', 'description_it', 'description_ko', 'description_ja', 'description_vi', 'description_zh', ],
        'all' => ['id', 'group', 'name', 'description', 'description_en', 'description_es', 'description_pt', 'description_de', 'description_fr', 'description_it', 'description_ko', 'description_ja', 'description_vi', 'description_zh',  'objectAttributes', 'objectFile', 'objectCategories'],
        '+' => [  'objectAttributes', 'objectFile', 'objectCategories']
    ];

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'group', 'name', 'description', 'description_en', 'description_es', 'description_pt', 'description_de', 'description_fr', 'description_it', 'description_ko', 'description_ja', 'description_vi', 'description_zh'], 'filter', 'filter' => 'trim'],
                
            [['name'], 'required'],
            [['group', 'name', 'description', 'description_en', 'description_es', 'description_pt', 'description_de', 'description_fr', 'description_it', 'description_ko', 'description_ja', 'description_vi', 'description_zh'], 'string', 'max' => 255],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('SettingsText', 'ID'),
                    'group' => FHtml::t('SettingsText', 'Group'),
                    'name' => FHtml::t('SettingsText', 'Name'),
                    'description' => FHtml::t('SettingsText', 'Description'),
                    'description_en' => FHtml::t('SettingsText', 'Description En'),
                    'description_es' => FHtml::t('SettingsText', 'Description Es'),
                    'description_pt' => FHtml::t('SettingsText', 'Description Pt'),
                    'description_de' => FHtml::t('SettingsText', 'Description De'),
                    'description_fr' => FHtml::t('SettingsText', 'Description Fr'),
                    'description_it' => FHtml::t('SettingsText', 'Description It'),
                    'description_ko' => FHtml::t('SettingsText', 'Description Ko'),
                    'description_ja' => FHtml::t('SettingsText', 'Description Ja'),
                    'description_vi' => FHtml::t('SettingsText', 'Description Vi'),
                    'description_zh' => FHtml::t('SettingsText', 'Description Zh'),
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
        $i18n->translations['SettingsText*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'SettingsText' => 'SettingsText.php',
            ],
        ];
    }

    public function toViewModel() {
    $model = new ViewModel();
            FHtml::setFieldValue($model, ['id'], $this->id);
            FHtml::setFieldValue($model, ['group'], $this->group);
            FHtml::setFieldValue($model, ['name'], $this->name);
            FHtml::setFieldValue($model, ['description'], $this->description);
            FHtml::setFieldValue($model, ['description_en'], $this->description_en);
            FHtml::setFieldValue($model, ['description_es'], $this->description_es);
            FHtml::setFieldValue($model, ['description_pt'], $this->description_pt);
            FHtml::setFieldValue($model, ['description_de'], $this->description_de);
            FHtml::setFieldValue($model, ['description_fr'], $this->description_fr);
            FHtml::setFieldValue($model, ['description_it'], $this->description_it);
            FHtml::setFieldValue($model, ['description_ko'], $this->description_ko);
            FHtml::setFieldValue($model, ['description_ja'], $this->description_ja);
            FHtml::setFieldValue($model, ['description_vi'], $this->description_vi);
            FHtml::setFieldValue($model, ['description_zh'], $this->description_zh);
        return $model;
    }
}
