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
 * This is the model class for table "cinema_brand".
 *

 * @property integer $id
 * @property string $image
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $website
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $type
 * @property string $status
 * @property integer $sort_order
 * @property integer $is_active
 * @property string $created_date
 * @property string $modified_date
 * @property string $application_id
 */
class CinemaBrandBase extends BaseModel //\yii\db\ActiveRecord
{

    /**
    * @inheritdoc
    */
    public $tableName = 'cinema_brand';

    public static function tableName()
    {
        return 'cinema_brand';
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
                    'id' => FHtml::t('CinemaBrand', 'ID'),
                    'image' => FHtml::t('CinemaBrand', 'Image'),
                    'name' => FHtml::t('CinemaBrand', 'Name'),
                    'description' => FHtml::t('CinemaBrand', 'Description'),
                    'content' => FHtml::t('CinemaBrand', 'Content'),
                    'website' => FHtml::t('CinemaBrand', 'Website'),
                    'address' => FHtml::t('CinemaBrand', 'Address'),
                    'phone' => FHtml::t('CinemaBrand', 'Phone'),
                    'email' => FHtml::t('CinemaBrand', 'Email'),
                    'type' => FHtml::t('CinemaBrand', 'Type'),
                    'status' => FHtml::t('CinemaBrand', 'Status'),
                    'sort_order' => FHtml::t('CinemaBrand', 'Sort Order'),
                    'is_active' => FHtml::t('CinemaBrand', 'Is Active'),
                    'created_date' => FHtml::t('CinemaBrand', 'Created Date'),
                    'modified_date' => FHtml::t('CinemaBrand', 'Modified Date'),
                    'application_id' => FHtml::t('CinemaBrand', 'Application ID'),
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
        $i18n->translations['CinemaBrand*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/modules/cinema/messages',
            'fileMap' => [
                'CinemaBrand' => 'CinemaBrand.php',
            ],
        ];
    }



}
