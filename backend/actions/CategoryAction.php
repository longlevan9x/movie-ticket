<?php
namespace backend\actions;

use backend\models\ObjectCategory;
use common\components\FHtml;
use common\components\Response;

class CategoryAction extends BaseAction
{
    public $is_secured = true;

    public function run()
    {
        if (($re = $this->isAuthorized()) !== true)
            return $re;

        $categories = FHtml::getModels(FHtml::TABLE_CATEGORIES, [], 'name asc', -1, 1, true);

        $data = array();
        foreach ($categories as $item) {
            $data[] = $item;
        }

        return Response::getOutputForAPI($data, \Globals::SUCCESS, 'OK', ['code' => 200]);
    }

}

