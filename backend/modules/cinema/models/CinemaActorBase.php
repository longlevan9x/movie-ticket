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
 * This is the model class for table "cinema_actor".
 *

 * @property integer $id
 * @property string $image
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $country
 * @property string $dob
 * @property string $gender
 * @property string $type
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $status
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class CinemaActorBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'cinema_actor';

    public static function tableName()
    {
        return 'cinema_actor';
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
                    'id' => FHtml::t('CinemaActor', 'ID'),
                    'image' => FHtml::t('CinemaActor', 'Image'),
                    'name' => FHtml::t('CinemaActor', 'Name'),
                    'description' => FHtml::t('CinemaActor', 'Description'),
                    'content' => FHtml::t('CinemaActor', 'Content'),
                    'country' => FHtml::t('CinemaActor', 'Country'),
                    'dob' => FHtml::t('CinemaActor', 'Dob'),
                    'gender' => FHtml::t('CinemaActor', 'Gender'),
                    'type' => FHtml::t('CinemaActor', 'Type'),
                    'sort_order' => FHtml::t('CinemaActor', 'Sort Order'),
                    'is_active' => FHtml::t('CinemaActor', 'Is Active'),
                    'status' => FHtml::t('CinemaActor', 'Status'),
                    'created_date' => FHtml::t('CinemaActor', 'Created Date'),
                    'modified_date' => FHtml::t('CinemaActor', 'Modified Date'),
                    'application_id' => FHtml::t('CinemaActor', 'Application ID'),
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
        $i18n->translations['CinemaActor*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/cinema/messages',
            'fileMap' => [
                'CinemaActor' => 'CinemaActor.php',
            ],
        ];
    }



}
