<?php

namespace backend\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;

/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "object_attributes".
 */
class ObjectAttributes extends ObjectAttributesBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    public static function getLookupArray($column)
    {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
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
        $i18n->translations['ObjectAttributes*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectAttributes' => 'ObjectAttributes.php',
            ],
        ];
    }

    public function toViewModel()
    {
        $model = new ViewModel();
        FHtml::setFieldValue($model, ['object_id'], $this->object_id);
        FHtml::setFieldValue($model, ['object_type'], $this->object_type);
        FHtml::setFieldValue($model, ['meta_key'], $this->meta_key);
        FHtml::setFieldValue($model, ['meta_value'], $this->meta_value);
        FHtml::setFieldValue($model, ['is_active'], $this->is_active);
        FHtml::setFieldValue($model, ['created_date'], $this->created_date);
        FHtml::setFieldValue($model, ['created_by'], $this->created_by);
        FHtml::setFieldValue($model, ['application_id'], $this->application_id);
        return $model;
    }
}
