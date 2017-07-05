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
 * This is the customized model class for table "booking".
 */
class Booking extends BookingBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'is_active desc,created_date desc,';

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
        
            [['id', 'seat_id', 'schedule_id', 'is_active', 'type', 'note', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['seat_id', 'schedule_id', 'is_active', 'type', 'created_date', 'created_user'], 'required'],
            [['seat_id', 'schedule_id', 'is_active'], 'integer'],
            [['created_date'], 'safe'],
            [['type', 'created_user', 'application_id'], 'string', 'max' => 100],
            [['note'], 'string', 'max' => 2000],
        ];
    }



    // Lookup Object: seat\n
    public $seat;
    public function getSeat() {
        if (!isset($this->seat))
        $this->seat = FHtml::getModel('booking_seat', '', $this->seat_id, '', false);

        return $this->seat;
    }
    public function setSeat($value) {
        $this->seat = $value;
    }


    public function prepareCustomFields() {
        parent::prepareCustomFields();

        $this->seat = self::getSeat();
    }


    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
    }
}
