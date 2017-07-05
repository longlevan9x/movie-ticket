<?php
/**
 * MIT licence
 * Version 1.0.1
 * Sjaak Priester, Amsterdam 28-08-2014.
 *
 * Sortable GridView for Yii 2.0
 *
 * GridView which is made sortable by means of the jQuery Sortable widget.
 * After each order operation, order data are posted to $orderUrl in the following format:
 * - $_POST["key"] - the primary key of the sorted ActiveRecord,
 * - $_POST["pos"] - the new position, zero-indexed.
 *
 */

namespace common\widgets;

use common\components\FHtml;
use kartik\grid\ActionColumn;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\jui\JuiAsset;
use yii\web\JsExpression;
use yii\widgets\ListView;
use Yii;

/**
 * Class SortableGridView
 * @package sjaakp\sortable
 */
class FListView extends ListView
{
    /**
     * @var array|string
     * The url which is called after an order operation.
     * The format is that of yii\helpers\Url::toRoute.
     * The url will be called with the POST method and the following data:
     * - key    the primary key of the ordered ActiveRecord,
     * - pos    the new, zero-indexed position.
     *
     * Example: ['movie/order-actor', 'id' => 5]
     */
    public $orderUrl;
    public $object_type;
    public $sort_field;
    public $display_type;
    public $buttons = [];
    public $visibleButtons = [];
    public $buttonOptions = [];
    public $dropdownButton = ['class' => 'btn btn-default'];
    public $urlCreator;
    public $params = [];

    public $field_id = ['id', 'product_id'];
    public $field_name = ['name', 'title', 'username'];
    public $field_image = ['image', 'avatar', 'username'];
    public $field_description = ['description', 'overview'];
    public $field_group = ['category_id', 'type', 'status', 'is_active', 'is_hot', 'is_top', 'is_promotion'];
    public $field_user = ['created_date', 'created_user', 'created_at', 'created_by'];
    public $field_business = [];
    public $field_count = ['count_views', 'count_likes', 'count_purchase'];

    public $itemLayout = FHtml::LAYOUT_NO_LABEL;
    public $itemCss = 'list-item';

    public $_isDropdown = true;
    const VIEW_DEFAULT = 'list';
    const VIEW_LIST = 'list';
    const VIEW_GRID_SMALL = 'grid2';
    const VIEW_GRID_BIG = 'grid3';
    const VIEW_MOBILE = 'mobile';

    const VIEW_GRID = 'grid';
    const VIEW_PRINT = 'print';
    const VIEW_IMAGE = 'image';

    public $template = '{view} {update} {delete}';
    public $viewOptions = [];
    public $updateOptions = [];
    public $deleteOptions = [];
    public $dropdownMenu = ['class' => 'text-left'];
    public $dropdownOptions = [];
    public $itemSize = 3;
    public $toolbar = '';

    /**
     * @var array
     * The options for the jQuery sortable object.
     * See http://api.jqueryui.com/sortable/ .
     * Notice that the options 'helper' and 'update' will be overwritten.
     * Default: empty array.
     */
    public $sortOptions = [];

    /**
     * @var boolean|string
     * The 'axis'-option of the jQuery sortable. If false, it is not set.
     */
    public $sortAxis = 'y';

    public function init()
    {
        $this->params = $this->getView()->params;

        self::initDefaultButtons();

        $this->urlCreator = function($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => FHtml::getFieldValue($model, ['id', 'product_id', 'object_type'])]);
        };

        if (empty($this->object_type))
            $this->object_type =  str_replace('-', '_', FHtml::currentController());
        else
            $this->object_type = str_replace('-', '_', $this->object_type);

        if (empty($this->sort_field)) {
            $this->sort_field = FHtml::field_exists(FHtml::createModel($this->object_type), 'sort_order') ? 'sort_order' : '';
        }

        if (empty($this->orderUrl))
            $this->orderUrl = '/api/sort-order';

        if (empty($this->display_type) || $this->display_type == self::VIEW_DEFAULT) //default view
        {
            if (empty($this->itemView))
                $this->itemView = function ($model, $key, $index, $widget) {
                    return "<tr><td class='col-xs-1'>" . FHtml::showImage($model) . "</td><td classs='col-xs-5'>". FHtml::showModel($model, $this->field_name, $this->field_description, $this->itemLayout ) . "</td><td classs='col-xs-4'>". FHtml::showModel($model, '', $this->field_business, FHtml::LAYOUT_TEXT) . "</td><td classs='col-xs-2'>" . FHtml::showModel($model, [], $this->field_group, $this->itemLayout ) . "</td><td classs='col-xs-1'><div class='pull-right'>". self::renderDataCellContent($model, null, null) . "</div></td></tr>";
                };
            $this->layout = "<div class='row clear-both'> <div class='hidden-print'>" . self::createToolbar() . " </div><div class='row' style='padding-left:25px;padding-right:25px;padding-top:10px'><table style='width:100%;' class='table table-bordered table-striped'> <tr><th class='col-xs-1'></th><th class='col-xs-5'> " . FHtml::t('Description') . "</th><th class='col-xs-4'>" . FHtml::t('Features') . "</th><th class='col-xs-2'></th><th class='col-xs-1'></th></tr>{items} </table> </div></div>";
        } else if ($this->display_type == self::VIEW_GRID) {
            if (empty($this->itemView))
                $this->itemView = function ($model, $key, $index, $widget) {
                $result = '';
                $columns = 12/$this->itemSize;
                if ($index == 0)
                    $result = '<div class="row" style="padding:5px">';

                if ($this->itemSize > 2) {
                    $result .= "<div class='col-md-$this->itemSize' style='padding:10px'><div class='col-md-12 border $this->itemCss' style=''><div class='col-md-2 no-padding'>" . FHtml::showImage($model) . "</div><div class='col-md-8'>" . FHtml::showModel($model, $this->field_name, $this->field_description, $this->itemLayout)  . "</div><div class='col-md-2 no-padding text-right hidden-print hidden-content'>" . self::renderDataCellContent($model, null, null) . "</div><div class='col-md-12 no-padding' style='margin-top:15px'>" . FHtml::showModel($model, '', $this->field_business, FHtml::LAYOUT_TABLE) . "</div></div></div>";
                } else if ($this->itemSize == 2) {
                    $result .= "<div class='col-md-$this->itemSize' style='padding:10px'><div class='col-md-12 border $this->itemCss' style='padding-bottom:10px'><div class='col-md-11 no-padding'>" . FHtml::showModel($model, $this->field_name, $this->field_description, $this->itemLayout) . "</div><div class='col-md-1 no-padding hidden-print hidden-content'>" . self::renderDataCellContent($model, null, null) . "</div></div></div>";
                } else if ($this->itemSize == 1) {
                    $result .= "<div class='col-md-$this->itemSize' style='padding-left:10px; padding-right:10px'><div class='col-md-12 border $this->itemCss' style=''><div class='text-wrap'>" . FHtml::showModel($model, $this->field_name, $this->field_description, $this->itemLayout) . "</div><div class='hidden-print hidden-content'>" . self::renderDataCellContent($model, null, null) . "</div></div></div>";
                }
                if ((($index + 1) % $columns == 0))
                    $result .= '</div><div class="row"  style="padding:5px">';
                return $result;
            };
            $this->layout = "<div class='row clear-both'> <div class='hidden-print'>" . self::createToolbar() . " </div> <div class='col-md-12'> {items} </div> <div class='row' style='padding:10px'>{pager}\n{summary}</div></div>";
        } else if ($this->display_type == self::VIEW_PRINT) {
            if (empty($this->itemView))
                $this->itemView = function ($model, $key, $index, $widget) {
                return "<tr><td class='col-xs-2'>" . FHtml::showImage($model) . "</td><td classs='col-xs-4'>". FHtml::showModel($model, $this->field_name, $this->field_description, $this->itemLayout ) . "</td><td classs='col-xs-3'>". FHtml::showModel($model, '', $this->field_business, FHtml::LAYOUT_TEXT) . "</td><td classs='col-xs-2'>" . FHtml::showModel($model, [], $this->field_group, $this->itemLayout ) . "</td></tr>";
            };
            $this->layout = "<div class='row clear-both'> <div class='hidden-print'>" . self::createToolbar() . " </div><div class='row' style='padding-left:25px;padding-right:25px;padding-top:10px'><table style='width:100%;' class='table table-bordered table-striped table-print'> <tr><th class='col-xs-2'></th><th class='col-xs-6'> " . FHtml::t('Description') . "</th><th class='col-xs-3'>" . FHtml::t('Features') . "</th><th class='col-xs-2'></th></tr>{items} </table> </div></div>";
        } else if ($this->display_type == self::VIEW_IMAGE) {
            if (empty($this->itemView))
                $this->itemView = function ($model, $key, $index, $widget) {
                    $result = "<div class='col-md-$this->itemSize' style='padding:10px'><div class='col-md-12 border no-padding $this->itemCss' style=''>" . FHtml::showImage($model) . "<div class='row' style='padding:10px'><div class='col-md-9'>" . FHtml::showModel($model, $this->field_name, [], $this->itemLayout) . "</div><div class='col-md-3 text-right hidden-print hidden-content'>" . self::renderDataCellContent($model, null, null) . "</div></div></div></div>";
                    return $result;
                };
            $this->layout = "<div class='row clear-both'> <div class='hidden-print'>" . self::createToolbar() . " </div> <div class='col-md-12 no-padding'> {items} </div> <div class='row' style='padding:10px'>{pager}\n{summary}</div></div>";
        } else {
            //2017/3/6
            $this->itemView = function ($model, $key, $index, $widget) {
                return $this->getView()->render($this->display_type, ['model' => $model, 'object_type' => $this->object_type, 'key' => $key, 'index' => $index, 'widget' => $widget, 'dataProvider' => $this->dataProvider]);
            };
            $this->layout = "<div class='row clear-both'> <div class='hidden-print'>" . self::createToolbar() . " </div> <div class='col-md-12'> {items} </div> <div class='row' style='padding:10px'><div class='col-md-12'>{pager}\n{summary}</div></div></div>";
        }
        $this->itemOptions = !empty($this->itemOptions) ? $this->itemOptions : [
            'tag' => false,
        ];


        parent::init();
    }

    public function renderEmpty()
    {
        return "<div class='row clear-both'>" . self::createToolbar() . "<div style='padding:10px'>" . parent::renderEmpty() . "</div></div>";
    }

    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url) {
                $options = $this->viewOptions;
                //$options['class'] = 'btn btn-xs';
                $title = FHtml::t('View');
                $icon = '<span class="glyphicon glyphicon-eye-open"></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = array_replace_recursive(['title' => $title, 'data-pjax' => '0'], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url) {
                $options = $this->updateOptions;
                //$options['class'] = 'btn btn-xs btn-info';

                $title = FHtml::t('Update');
                $icon = '<span class="glyphicon glyphicon-pencil"></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = array_replace_recursive(['title' => $title, 'data-pjax' => '0'], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url) {
                $options = $this->deleteOptions;
                //$options['class'] = 'btn btn-xs btn-danger';
                //$options['style'] = 'border:solid 1px lightgray';

                $title = FHtml::t('Delete');
                $icon = '<span class="glyphicon glyphicon-trash"></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = array_replace_recursive(
                    [
                        'title' => $title,
                        'data-confirm' => FHtml::t('Are you sure to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0'
                    ],
                    $options
                );
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }
        $this->_isDropdown = $this->params['buttonsType'];
        $moduleName = FHtml::currentModule() . FHtml::currentController();
        $currentRole = FHtml::getCurrentRole();

        $this->visibleButtons = [
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ];

        $this->options = [
            'tag' => 'div',
            'class' => 'list-wrapper',
            'id' => $this->id,
        ];
        $this->viewOptions = ['role'=>'modal-remote','title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'];
        $this->updateOptions = ['role'=>$this->params['displayType'],'title'=>FHtml::t('common', 'title.update'), 'data-toggle'=>'tooltip'];
        $this->deleteOptions = [
            'role'=>'modal-remote',
            'title'=>FHtml::t('common', 'title.delete'),
            'data-confirm'=>false,
            'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>FHtml::t('common', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'messege.confirmdelete')
        ];
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $this->_isDropdown = true; // always true
        $content = preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
        $name = $matches[1];

        if (isset($this->visibleButtons[$name])) {
            $isVisible = $this->visibleButtons[$name] instanceof \Closure
                ? call_user_func($this->visibleButtons[$name], $model, $key, $index)
                : $this->visibleButtons[$name];
        } else {
            $isVisible = true;
        }

        if ($isVisible && isset($this->buttons[$name])) {
            $url = $this->createUrl($name, $model, $key, $index);
            return call_user_func($this->buttons[$name], $url, $model, $key);
        } else {
            return '';
        }
    }, $this->template);
        $options = $this->dropdownButton;
        if ($this->_isDropdown) {
            $label = ArrayHelper::remove($options, 'label', '');
            $caret = ArrayHelper::remove($options, 'caret', ' <span class="caret"></span>');
            $options = array_replace_recursive($options, ['type' => 'button', 'data-toggle' => 'dropdown']);
            Html::addCssClass($options, 'dropdown-toggle');
            $button = Html::button($label . $caret, $options);
            Html::addCssClass($this->dropdownMenu, 'dropdown-menu');
            $dropdown = $button . PHP_EOL . Html::tag('ul', $content, $this->dropdownMenu);
            Html::addCssClass($this->dropdownOptions, 'dropdown');
            return Html::tag('div', $dropdown, $this->dropdownOptions);
        }
        return $content;
    }

    /**
     * Creates a URL for the given action and model.
     * This method is called for each button and each row.
     * @param string $action the button name (or action ID)
     * @param \yii\db\ActiveRecord $model the data model
     * @param mixed $key the key associated with the data model
     * @param integer $index the current row index
     * @return string the created URL
     */
    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index);
        } else {
            $params = is_array($key) ? $key : ['id' => (string) $key];
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

            return Url::toRoute($params);
        }
    }

    public function createToolbar() {
        if (empty($this->toolbar)) {
            $currentRole = FHtml::getCurrentRole();
            $moduleName = FHtml::currentModule();
            $createButton = '';
            if (FHtml::isInRole('', 'create', $currentRole)) {
                $createButton = FHtml::a('<i class="glyphicon glyphicon-plus"></i>&nbsp;' . FHtml::t('common', 'Create'), ['create'],
                    [
                        'role' => $this->params['editType'],
                        'data-pjax' => $this->params['isAjax'] == true ? 1 : 0,
                        'title' => FHtml::t('common', 'title.create'),
                        'class' => 'btn btn-success',
                        'style' => 'float:left;'
                    ]);
            }

            $this->toolbar = $createButton;
        }
        return "<div class='row' style='margin-left:10px;padding-bottom:5px;margin-right:10px'>" . $this->toolbar . "</div>";
    }
}
