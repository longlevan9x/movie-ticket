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
 * This is the model class for table "cinema_movie".
 *

 * @property string $id
 * @property string $image
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $director
 * @property string $writer
 * @property string $runtime
 * @property string $trailer
 * @property string $technology
 * @property string $mpaa
 * @property string $country
 * @property integer $rates
 * @property string $count_rates
 * @property string $release_date
 * @property string $close_date
 * @property string $status
 * @property integer $sort_order
 * @property string $type
 * @property integer $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class CinemaMovieBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'cinema_movie';

    public static function tableName()
    {
        return 'cinema_movie';
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
                    'id' => FHtml::t('CinemaMovie', 'ID'),
                    'image' => FHtml::t('CinemaMovie', 'Image'),
                    'code' => FHtml::t('CinemaMovie', 'Code'),
                    'name' => FHtml::t('CinemaMovie', 'Name'),
                    'description' => FHtml::t('CinemaMovie', 'Description'),
                    'content' => FHtml::t('CinemaMovie', 'Content'),
                    'director' => FHtml::t('CinemaMovie', 'Director'),
                    'writer' => FHtml::t('CinemaMovie', 'Writer'),
                    'runtime' => FHtml::t('CinemaMovie', 'Runtime'),
                    'trailer' => FHtml::t('CinemaMovie', 'Trailer'),
                    'technology' => FHtml::t('CinemaMovie', 'Technology'),
                    'mpaa' => FHtml::t('CinemaMovie', 'Mpaa'),
                    'country' => FHtml::t('CinemaMovie', 'Country'),
                    'rates' => FHtml::t('CinemaMovie', 'Rates'),
                    'count_rates' => FHtml::t('CinemaMovie', 'Count Rates'),
                    'release_date' => FHtml::t('CinemaMovie', 'Release Date'),
                    'close_date' => FHtml::t('CinemaMovie', 'Close Date'),
                    'status' => FHtml::t('CinemaMovie', 'Status'),
                    'sort_order' => FHtml::t('CinemaMovie', 'Sort Order'),
                    'type' => FHtml::t('CinemaMovie', 'Type'),
                    'is_active' => FHtml::t('CinemaMovie', 'Is Active'),
                    'created_date' => FHtml::t('CinemaMovie', 'Created Date'),
                    'modified_date' => FHtml::t('CinemaMovie', 'Modified Date'),
                    'application_id' => FHtml::t('CinemaMovie', 'Application ID'),
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
        $i18n->translations['CinemaMovie*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/cinema/messages',
            'fileMap' => [
                'CinemaMovie' => 'CinemaMovie.php',
            ],
        ];
    }



}
