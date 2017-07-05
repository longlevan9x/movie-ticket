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
 * This is the model class for table "object_category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $image
 * @property string $name
 * @property string $description
 * @property integer $sort_order
 * @property integer $is_active
 * @property integer $is_top
 * @property integer $is_hot
 * @property string $object_type
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class ObjectCategoryBase extends BaseModel //\yii\db\ActiveRecord
{

// id, parent_id, image, name, description, sort_order, is_active, is_top, is_hot, object_type, created_date, updated_date
    const COLUMN_ID = 'id';
    const COLUMN_PARENT_ID = 'parent_id';
    const COLUMN_IMAGE = 'image';
    const COLUMN_NAME = 'name';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_SORT_ORDER = 'sort_order';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_IS_TOP = 'is_top';
    const COLUMN_IS_HOT = 'is_hot';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_UPDATED_DATE = 'modified_date';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_category';

    public static function tableName()
    {
        return 'object_category';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['id', 'parent_id', 'image', 'name', 'description', 'sort_order', 'is_active', 'is_top', 'is_hot', 'object_type', 'created_date', 'modified_date'], 'filter', 'filter' => 'trim'],

            [['parent_id', 'sort_order', 'is_active', 'is_top', 'is_hot'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['image', 'name'], 'string', 'max' => 255],
            [['object_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FHtml::t('ObjectCategory', 'ID'),
            'parent_id' => FHtml::t('ObjectCategory', 'Parent ID'),
            'image' => FHtml::t('ObjectCategory', 'Image'),
            'name' => FHtml::t('ObjectCategory', 'Name'),
            'description' => FHtml::t('ObjectCategory', 'Description'),
            'sort_order' => FHtml::t('ObjectCategory', 'Sort Order'),
            'is_active' => FHtml::t('ObjectCategory', 'Is Active'),
            'is_top' => FHtml::t('ObjectCategory', 'Is Top'),
            'is_hot' => FHtml::t('ObjectCategory', 'Is Hot'),
            'object_type' => FHtml::t('ObjectCategory', 'Object Type'),
            'created_date' => FHtml::t('ObjectCategory', 'Created Date'),
            'modified_date' => FHtml::t('ObjectCategory', 'Modified Date'),
        ];
    }


}
