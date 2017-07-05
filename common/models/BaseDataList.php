<?php

namespace common\models;

use common\components\FHtml;
use yii\data\ActiveDataProvider;

class BaseDataList extends ActiveDataProvider
{
    public $viewModels;
    public $page_size;
    public $models;

    public function getViewModels()
    {
        if (!isset($this->viewModels)) {
            $models = $this->getModels();

            $this->viewModels = FHtml::toViewModel($models);
        }

        return $this->viewModels;
    }

    public function getModels()
    {
        if (isset($this->models))
            return $this->models;

        $this->models = $this->query->all();
        return $this->models;
    }
}
