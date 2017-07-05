<?php

namespace backend\models;

use Yii;

/**
 * @property string $id
 * @property string $object_id
 * @property string $object_type
 * @property string $object2_id
 * @property string $object2_type
 * @property string $relation_type
 * @property integer $sort_order
 * @property string $created_date
 * @property string $created_user
 */
class AuthPermissionBase extends \yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'auth_permission';

    public static function tableName()
    {
        return 'auth_permission';
    }

}
