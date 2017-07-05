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
 * This is the model class for table "cinema".
 *

 * @property integer $id
 * @property integer $brand_id
 * @property string $image
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $phone
 * @property string $address
 * @property string $city
 * @property string $country
 * @property string $type
 * @property string $status
 * @property integer $is_active
 * @property integer $sort_order
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class CinemaBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'cinema';

    public static function tableName()
    {
        return 'cinema';
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
                    'id' => FHtml::t('Cinema', 'ID'),
                    'brand_id' => FHtml::t('Cinema', 'Brand ID'),
                    'image' => FHtml::t('Cinema', 'Image'),
                    'code' => FHtml::t('Cinema', 'Code'),
                    'name' => FHtml::t('Cinema', 'Name'),
                    'description' => FHtml::t('Cinema', 'Description'),
                    'content' => FHtml::t('Cinema', 'Content'),
                    'phone' => FHtml::t('Cinema', 'Phone'),
                    'address' => FHtml::t('Cinema', 'Address'),
                    'city' => FHtml::t('Cinema', 'City'),
                    'country' => FHtml::t('Cinema', 'Country'),
                    'type' => FHtml::t('Cinema', 'Type'),
                    'status' => FHtml::t('Cinema', 'Status'),
                    'is_active' => FHtml::t('Cinema', 'Is Active'),
                    'sort_order' => FHtml::t('Cinema', 'Sort Order'),
                    'created_date' => FHtml::t('Cinema', 'Created Date'),
                    'modified_date' => FHtml::t('Cinema', 'Modified Date'),
                    'application_id' => FHtml::t('Cinema', 'Application ID'),
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
        $i18n->translations['Cinema*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/cinema/messages',
            'fileMap' => [
                'Cinema' => 'Cinema.php',
            ],
        ];
    }



}
