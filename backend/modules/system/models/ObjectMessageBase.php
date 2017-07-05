<?php

namespace backend\modules\system\models;

use Yii;
use common\components\FHtml;
use common\components\FModel;
use common\models\BaseModel;
use frontend\models\ViewModel;

/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the model class for table "object_message".
 *

 * @property string $id
 * @property string $object_id
 * @property string $object_type
 * @property string $message
 * @property string $status
 * @property string $type
 * @property string $method
 * @property string $sent_date
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class ObjectMessageBase extends BaseModel //\yii\db\ActiveRecord
{
    const STATUS_PLAN = 'Plan';
    const STATUS_SENT = 'Sent';
    const STATUS_RECEIVED = 'Received';
    const STATUS_READ = 'Read';
    const TYPE_WARNING = 'Warning';
    const TYPE_BIRTHDAY = 'Birthday';
    const TYPE_REMIND = 'Remind';
    const TYPE_PROMOTION = 'Promotion';
    const METHOD_PUSH = 'Push';
    const METHOD_EMAIL = 'Email';
    const METHOD_SMS = 'SMS';

// id, object_id, object_type, message, status, type, method, sent_date, created_date, created_user, application_id
    const COLUMN_ID = 'id';
    const COLUMN_OBJECT_ID = 'object_id';
    const COLUMN_OBJECT_TYPE = 'object_type';
    const COLUMN_MESSAGE = 'message';
    const COLUMN_STATUS = 'status';
    const COLUMN_TYPE = 'type';
    const COLUMN_METHOD = 'method';
    const COLUMN_SENT_DATE = 'sent_date';
    const COLUMN_CREATED_DATE = 'created_date';
    const COLUMN_CREATED_USER = 'created_user';
    const COLUMN_APPLICATION_ID = 'application_id';

    /**
    * @inheritdoc
    */
    public $tableName = 'object_message';

    public static function tableName()
    {
        return 'object_message';
    }



    /**
     * @inheritdoc
     * @return ObjectMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectMessageQuery(get_called_class());
    }
}
