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
 * This is the model class for table "cinema_hall".
 *

 * @property integer $id
 * @property string $name
 * @property integer $seat_count
 * @property string $status
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $type
 * @property string $created_date
 * @property string $modified_date
 */
class CinemaHallBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'cinema_hall';

    public static function tableName()
    {
        return 'cinema_hall';
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
                    'id' => FHtml::t('CinemaHall', 'ID'),
                    'name' => FHtml::t('CinemaHall', 'Name'),
                    'seat_count' => FHtml::t('CinemaHall', 'Seat Count'),
                    'status' => FHtml::t('CinemaHall', 'Status'),
                    'is_active' => FHtml::t('CinemaHall', 'Is Active'),
                    'sort_order' => FHtml::t('CinemaHall', 'Sort Order'),
                    'type' => FHtml::t('CinemaHall', 'Type'),
                    'created_date' => FHtml::t('CinemaHall', 'Created Date'),
                    'modified_date' => FHtml::t('CinemaHall', 'Modified Date'),
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
        $i18n->translations['CinemaHall*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/cinema/messages',
            'fileMap' => [
                'CinemaHall' => 'CinemaHall.php',
            ],
        ];
    }



}
