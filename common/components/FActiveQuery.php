<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


use yii\db\ActiveQuery;


class FActiveQuery extends ActiveQuery
{
    public function all($db = null)
    {
        $items = parent::all($db);
        return FHtml::getTranslatedModels($items);
    }
}