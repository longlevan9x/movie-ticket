<?php

namespace backend\modules\booking\models;

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
 * This is the model class for table "booking_schedule".
 *

 * @property integer $id
 * @property string $object_id
 * @property string $object_type
 * @property string $date
 * @property string $start_time
 * @property string $finish_time
 * @property integer $is_active
 * @property string $application_id
 */
class BookingScheduleBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'booking_schedule';

    public static function tableName()
    {
        return 'booking_schedule';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
                    'id' => FHtml::t('BookingSchedule', 'ID'),
                    'object_id' => FHtml::t('BookingSchedule', 'Object ID'),
                    'object_type' => FHtml::t('BookingSchedule', 'Object Type'),
                    'date' => FHtml::t('BookingSchedule', 'Date'),
                    'start_time' => FHtml::t('BookingSchedule', 'Start Time'),
                    'finish_time' => FHtml::t('BookingSchedule', 'Finish Time'),
                    'is_active' => FHtml::t('BookingSchedule', 'Is Active'),
                    'application_id' => FHtml::t('BookingSchedule', 'Application ID'),
                ];
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
        $i18n->translations['BookingSchedule*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/booking/messages',
            'fileMap' => [
                'BookingSchedule' => 'BookingSchedule.php',
            ],
        ];
    }



}
