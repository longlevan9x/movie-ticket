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
 * This is the customized model class for table "booking_schedule".
 */
class BookingSchedule extends BookingScheduleBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'is_active desc,';

    const OBJECTS_RELATED = [];
    const OBJECTS_META = [];

    public static function getLookupArray($column) {
        if (key_exists($column, self::LOOKUP))
            return self::LOOKUP[$column];
        return [];
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
        
            [['id', 'object_id', 'object_type', 'date', 'start_time', 'finish_time', 'is_active', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['object_id', 'object_type', 'date', 'start_time', 'finish_time', 'is_active'], 'required'],
            [['date', 'start_time', 'finish_time'], 'safe'],
            [['is_active'], 'integer'],
            [['object_id', 'object_type', 'application_id'], 'string', 'max' => 100],
        ];
    }



    // Lookup Object: object\n
    public $object;
    public function getObject() {
        if (!isset($this->object))
        $this->object = FHtml::getModel('cinema_movie', '', $this->object_id, '', false);

        return $this->object;
    }
    public function setObject($value) {
        $this->object = $value;
    }


    public function prepareCustomFields() {
        parent::prepareCustomFields();

        $this->object = self::getObject();
    }


    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
    }
}
