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
 * This is the model class for table "booking_seat".
 *

 * @property integer $id
 * @property string $object_id
 * @property string $object_type
 * @property string $name
 * @property string $type
 * @property string $status
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $application_id
 */
class BookingSeatBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'booking_seat';

    public static function tableName()
    {
        return 'booking_seat';
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
                    'id' => FHtml::t('BookingSeat', 'ID'),
                    'object_id' => FHtml::t('BookingSeat', 'Object ID'),
                    'object_type' => FHtml::t('BookingSeat', 'Object Type'),
                    'name' => FHtml::t('BookingSeat', 'Name'),
                    'type' => FHtml::t('BookingSeat', 'Type'),
                    'status' => FHtml::t('BookingSeat', 'Status'),
                    'sort_order' => FHtml::t('BookingSeat', 'Sort Order'),
                    'is_active' => FHtml::t('BookingSeat', 'Is Active'),
                    'application_id' => FHtml::t('BookingSeat', 'Application ID'),
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
        $i18n->translations['BookingSeat*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/booking/messages',
            'fileMap' => [
                'BookingSeat' => 'BookingSeat.php',
            ],
        ];
    }



}
