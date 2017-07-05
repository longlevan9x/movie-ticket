<?php

namespace backend\models;

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
 * This is the customized model class for table "object_category".
 */
class ObjectCategory extends ObjectCategoryBase //\yii\db\ActiveRecord
{
    public $action;
    public $control;
    public $params;

    public static function tableSchema()
    {
        return FHtml::getTableSchema(self::tableName());
    }

    public $order_by = 'sort_order asc, name asc, is_active desc,is_top desc,created_date desc';

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
        $i18n->translations['ObjectCategory*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@backend/messages',
            'fileMap' => [
                'ObjectCategory' => 'ObjectCategory.php',
            ],
        ];
    }

    public function getImageurl()
    {
        $url = Yii::$app->request->baseUrl . '/images/category/' . $this->image;
        return str_replace('web/', '', $url);
    }

    public static function itemAlias($type, $code = NULL)
    {
        $_items = array(
            'status' => array(
                '0' => 'Inactive',
                '1' => 'Active',
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    public function getProducts()
    {
        $list = FHtml::getProducts('category_id like "%' . $this->id . '%"');
        return $list;
    }

    public static function getDb()
    {
        return FHtml::currentDb();
    }


}
