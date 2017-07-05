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
 * This is the customized model class for table "cinema_movie".
 */
class CinemaMovie extends CinemaMovieBase //\yii\db\ActiveRecord
{
    const LOOKUP = [];

    const COLUMNS_UPLOAD = ['image',];

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
        
            [['id', 'image', 'code', 'name', 'description', 'content', 'director', 'writer', 'runtime', 'trailer', 'technology', 'mpaa', 'country', 'rates', 'count_rates', 'release_date', 'close_date', 'status', 'sort_order', 'type', 'is_active', 'created_date', 'modified_date', 'application_id'], 'filter', 'filter' => 'trim'],
                
            [['image', 'code', 'name', 'runtime', 'trailer', 'technology', 'mpaa', 'release_date', 'close_date', 'is_active', 'created_date'], 'required'],
            [['content'], 'string'],
            [['rates', 'count_rates', 'sort_order', 'is_active'], 'integer'],
            [['release_date', 'close_date', 'created_date', 'modified_date'], 'safe'],
            [['image', 'code', 'name', 'runtime', 'trailer'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['director', 'writer', 'technology', 'country', 'status', 'type', 'application_id'], 'string', 'max' => 100],
            [['mpaa'], 'string', 'max' => 20],
        ];
    }




    public function prepareCustomFields() {
        parent::prepareCustomFields();

    }


    public static function getRelatedObjects() {
        return self::OBJECTS_RELATED;
    }

    public static function getMetaObjects() {
        return self::OBJECTS_META;
    }
}
