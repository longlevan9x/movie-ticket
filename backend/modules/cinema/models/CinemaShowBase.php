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
 * This is the model class for table "cinema_show".
 *

 * @property integer $id
 * @property string $cinema_id
 * @property string $hall_id
 * @property string $movie_id
 * @property string $start_date
 * @property string $end_date
 * @property integer $sort_order
 * @property string $type
 * @property integer $is_active
 * @property string $status
 * @property string $created_date
 * @property string $created_user
 * @property string $application_id
 */
class CinemaShowBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'cinema_show';

    public static function tableName()
    {
        return 'cinema_show';
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
                    'id' => FHtml::t('CinemaShow', 'ID'),
                    'cinema_id' => FHtml::t('CinemaShow', 'Cinema ID'),
                    'hall_id' => FHtml::t('CinemaShow', 'Hall ID'),
                    'movie_id' => FHtml::t('CinemaShow', 'Movie ID'),
                    'start_date' => FHtml::t('CinemaShow', 'Start Date'),
                    'end_date' => FHtml::t('CinemaShow', 'End Date'),
                    'sort_order' => FHtml::t('CinemaShow', 'Sort Order'),
                    'type' => FHtml::t('CinemaShow', 'Type'),
                    'is_active' => FHtml::t('CinemaShow', 'Is Active'),
                    'status' => FHtml::t('CinemaShow', 'Status'),
                    'created_date' => FHtml::t('CinemaShow', 'Created Date'),
                    'created_user' => FHtml::t('CinemaShow', 'Created User'),
                    'application_id' => FHtml::t('CinemaShow', 'Application ID'),
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
        $i18n->translations['CinemaShow*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/cinema/messages',
            'fileMap' => [
                'CinemaShow' => 'CinemaShow.php',
            ],
        ];
    }



}
