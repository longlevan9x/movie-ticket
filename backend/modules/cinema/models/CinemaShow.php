<?php

namespace backend\modules\cinema\models;

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
 * This is the customized model class for table "cinema_show".
 */
class CinemaShow extends CinemaShowBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = [];

    public $order_by = 'sort_order asc,is_active desc,created_date desc,';

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
        
            [['id', 'cinema_id', 'hall_id', 'movie_id', 'start_date', 'end_date', 'sort_order', 'type', 'is_active', 'status', 'created_date', 'created_user', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['cinema_id', 'hall_id', 'movie_id', 'start_date', 'end_date', 'created_date'], 'required'],
            [['start_date', 'end_date', 'created_date'], 'safe'],
            [['sort_order', 'is_active'], 'integer'],
            [['cinema_id', 'hall_id', 'movie_id', 'type', 'status', 'created_user', 'application_id'], 'string', 'max' => 100],
        ];
    }



    // Lookup Object: cinema\n
    public $cinema;
    public function getCinema() {
        if (!isset($this->cinema))
        $this->cinema = FHtml::getModel('cinema', '', $this->cinema_id, '', false);

        return $this->cinema;
    }
    public function setCinema($value) {
        $this->cinema = $value;
    }

    // Lookup Object: hall\n
    public $hall;
    public function getHall() {
        if (!isset($this->hall))
        $this->hall = FHtml::getModel('cinema_hall', '', $this->hall_id, '', false);

        return $this->hall;
    }
    public function setHall($value) {
        $this->hall = $value;
    }

    // Lookup Object: movie\n
    public $movie;
    public function getMovie() {
        if (!isset($this->movie))
        $this->movie = FHtml::getModel('cinema_movie', '', $this->movie_id, '', false);

        return $this->movie;
    }
    public function setMovie($value) {
        $this->movie = $value;
    }


    public function prepareCustomFields() {
        parent::prepareCustomFields();

        $this->cinema = self::getCinema();
        $this->hall = self::getHall();
        $this->movie = self::getMovie();
    }


    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
    }
}
