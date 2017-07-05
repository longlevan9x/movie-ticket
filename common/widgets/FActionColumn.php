<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @package yii2-grid
 * @version 3.1.2
 */

namespace common\widgets;

use kartik\grid\ActionColumn;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Extends the Yii's ActionColumn for the Grid widget [[\kartik\widgets\GridView]] with various enhancements.
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class FActionColumn extends ActionColumn
{
    public $actionLayout = '{delete}';

    public function init()
    {
       parent::init();
    }

    /**
     * Render default action buttons
     *
     * @return string
     */
    protected function initDefaultButtons()
    {
        return parent::initDefaultButtons();
    }

    /**
     * @inheritdoc
     */
    public function renderDataCell($model, $key, $index)
    {
        return parent::renderDataCell($model, $key, $index);
    }

    /**
     * Renders the data cell.
     *
     * @param Model $model
     * @param mixed $key
     * @param int   $index
     *
     * @return mixed|string
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->dropdown == 'ajax') {
            $result = $this->actionLayout;
            $js_function = BaseInflector::camelize($this->grid->object_type) . '("' . $model->id . '")';
            $result = str_replace('{delete}', "<a class='btn btn-sm btn-danger' href='#' onclick='delete{$js_function}'> x </a>", $result);
            $result = str_replace('{reset}', "<a class='btn btn-sm btn-warning' href='#' onclick='reset{$js_function}'> x </a>", $result);

            return $result;
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }
    }
}
