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
 * This is the model class for table "object_file".
 *
 * @property string $id
 * @property integer $object_id
 * @property string $object_type
 * @property string $file
 * @property string $title
 * @property string $description
 * @property string $file_type
 * @property string $file_size
 * @property string $file_duration
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ObjectFileBase extends BaseModel //\yii\db\ActiveRecord
{

// id, object_id, object_type, file, title, description, file_type, file_size, file_duration, is_active, sort_order, created_date, created_user, application_id
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_FILE = 'file';
    const COLUMN_TITLE = 'title';
    const COLUMN_DESCRIPTION = 'description';
    const COLUMN_FILE_TYPE = 'file_type';
    const COLUMN_FILE_SIZE = 'file_size';
    const COLUMN_FILE_DURATION = 'file_duration';
    const COLUMN_IS_ACTIVE = 'is_active';
    const COLUMN_SORT_ORDER = 'sort_order';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_CREATED_USER = 'created_user';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
     * @inheritdoc
     */
    public $tableName = 'object_file';

    public static function tableName()
    {
        return 'object_file';
    }


    /**
     * @inheritdoc
     * @return ObjectFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectFileQuery(get_called_class());
    }
}
