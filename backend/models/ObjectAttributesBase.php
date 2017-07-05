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
 * This is the model class for table "object_attributes".
 *

 * @property integer $object_id
 * @property string $object_type
 * @property string $meta_key
 * @property string $meta_value
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_by
 * @property string $application_id
 */
class ObjectAttributesBase extends BaseModel //\yii\db\ActiveRecord
{
    // Constants

    // object_id, object_type, meta_key, meta_value, is_active, created_date, created_by, application_id
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_META_KEY = 'meta_key';
    const COLUMN_META_VALUE = 'meta_value';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_CREATED_BY = 'created_by';
    const COLUMN_APPLICATION_ID = 'application_id';
    
    const COLUMNS_UPLOAD = [];

    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'object_attributes';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['object_id', 'object_type', 'meta_key', 'meta_value', 'is_active', 'created_date', 'created_by', 'application_id'], 'filter', 'filter' => 'trim'],
        
            [['object_id', 'object_type', 'meta_key', 'is_active'], 'required'],
            [['object_id', 'is_active'], 'integer'],
            [['created_date'], 'safe'],
            [['object_type', 'meta_key', 'meta_value', 'created_by', 'application_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'object_id' => FHtml::t('ObjectAttributes', 'Object ID'),
            'object_type' => FHtml::t('ObjectAttributes', 'Object Type'),
            'meta_key' => FHtml::t('ObjectAttributes', 'Meta Key'),
            'meta_value' => FHtml::t('ObjectAttributes', 'Meta Value'),
            'is_active' => FHtml::t('ObjectAttributes', 'Is Active'),
            'created_date' => FHtml::t('ObjectAttributes', 'Created Date'),
            'created_by' => FHtml::t('ObjectAttributes', 'Created By'),
            'application_id' => FHtml::t('ObjectAttributes', 'Application ID'),
        ];
    }

    /**
     * @inheritdoc
     * @return ObjectAttributesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectAttributesQuery(get_called_class());
    }
}
