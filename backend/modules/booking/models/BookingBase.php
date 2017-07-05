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
 * This is the model class for table "booking".
 *

 * @property integer $id
 * @property integer $seat_id
 * @property integer $schedule_id
 * @property integer $is_active
 * @property string $type
 * @property string $note
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class BookingBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'booking';

    public static function tableName()
    {
        return 'booking';
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
                    'id' => FHtml::t('Booking', 'ID'),
                    'seat_id' => FHtml::t('Booking', 'Seat ID'),
                    'schedule_id' => FHtml::t('Booking', 'Schedule ID'),
                    'is_active' => FHtml::t('Booking', 'Is Active'),
                    'type' => FHtml::t('Booking', 'Type'),
                    'note' => FHtml::t('Booking', 'Note'),
                    'created_date' => FHtml::t('Booking', 'Created Date'),
                    'created_user' => FHtml::t('Booking', 'Created User'),
                    'application_id' => FHtml::t('Booking', 'Application ID'),
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
        $i18n->translations['Booking*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/booking/messages',
            'fileMap' => [
                'Booking' => 'Booking.php',
            ],
        ];
    }



}
