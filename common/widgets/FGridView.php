<?php

/**
 * @package   yii2-grid
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @version   3.1.2
 */

namespace common\widgets;

use common\components\FConfig;
use common\components\FHtml;
use common\widgets\FListView;
use kartik\editable\Editable;
use kartik\grid\DataColumn;
use kartik\grid\GridExportAsset;
use kartik\grid\GridFloatHeadAsset;
use kartik\grid\GridPerfectScrollbarAsset;
use kartik\grid\GridResizeColumnsAsset;
use kartik\grid\GridView;
use kartik\grid\GridViewAsset;
use kartik\grid\Module;
use kartik\grid\RadioColumn;
use kartik\grid\SerialColumn;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\ButtonDropdown;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\base\Config;
use kartik\sortable\SortableAsset;
use yii\jui\JuiAsset;

/**
 * Enhances the Yii GridView widget with various options to include Bootstrap specific styling enhancements. Also
 * allows to simply disable Bootstrap styling by setting `bootstrap` to false. Includes an extended data column for
 * column specific enhancements.
 *
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since  1.0
 */
class FGridView extends GridView
{
    public $orderUrl;
    public $sort_enabled = true;
    public $object_type;
    public $sort_field;

    public $filterEnabled = true;
    public $form_fields = [];
    public $default_fields = [];
    public $filter_fields = [];
    public $views_list = [];

    public $form_enabled = false;
    public $form_field_template = '{object}Search[{column}]';

    public $display_type;
    public $render_type = FConfig::RENDER_TYPE_AUTO;
    public $edit_type = FConfig::EDIT_TYPE_INLINE;
    public $readonly = null;

    public $sortOptions = [];
    public $sortAxis = 'y';

    public $field_id = ['id', 'product_id'];
    public $field_name = ['name', 'title', 'username'];
    public $field_image = ['image', 'avatar', 'username'];
    public $field_description = ['description', 'overview'];
    public $field_business = [];
    public $field_count = ['count_views', 'count_likes', 'count_purchase'];
    public $field_group = ['category_id', 'type', 'status', 'is_active', 'is_hot', 'is_top', 'is_promotion'];
    public $field_user = ['created_date', 'created_user', 'created_at', 'created_by'];
    public $itemView;
    public $layout = "{toolbar}\n{items}\n{summary}\n{pager}";

    public $actionLayout = "{delete}";

    /**
     * @inheritdoc
     */
    public function init()
    {
        FHtml::showCurrentMessages();

        $this->striped = isset($this->striped) ? $this->striped : FHtml::config('FGridView::striped', false, [], 'Theme', FHtml::EDITOR_BOOLEAN);
        $this->condensed = isset($this->condensed) ? $this->condensed : FHtml::config('FGridView::condensed', false, [], 'Theme', FHtml::EDITOR_BOOLEAN);
        $this->bordered = isset($this->bordered) ? $this->bordered : FHtml::config('FGridView::bordered', false, [], 'Theme', FHtml::EDITOR_BOOLEAN);
        $this->display_type = empty($this->display_type) ? FHtml::getRequestParam(['view'], FHtml::settingApplication('Grid Display Type', '')) : $this->display_type;

        $this->pjax = isset($this->pjax) ? true : false;

        $form_enabled = FHtml::getRequestParam('form_enabled', false);
        if (!empty($this->display_type) || $form_enabled)
        {
            $this->form_enabled = true;
        }

        //2017/3/5
        if ($this->readonly)
            $this->edit_type = FHtml::EDIT_TYPE_VIEW;

        if (empty($this->edit_type))
            $this->edit_type = FHtml::settingPageView('GRID EDIT TYPE', FHtml::EDIT_TYPE_INLINE, [FHtml::EDIT_TYPE_VIEW, FHtml::EDIT_TYPE_INLINE, FHtml::EDIT_TYPE_POPUP, FHtml::EDIT_TYPE_INPUT], '', FHtml::EDITOR_SELECT);

        if ($this->display_type == 'print') {
            $this->layout = '{toolbar}{items}';
            $this->filterModel = null;
            parent::init();
            return;
        }

        //Hung: intitialize objects
        if (empty($this->object_type))
            $this->object_type = FHtml::getTableName($this->filterModel);

        if (!isset($this->dataProvider)) {
            $this->dataProvider = new ActiveDataProvider();
        }


        parent::init();

        self::initSorting();
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function run()
    {
        if (FHtml::currentDevice()->isMobile()) {
            $listtype = FListView::VIEW_MOBILE;
            $this->display_type = FListView::VIEW_MOBILE;
            $this->field_name = [];
            $this->field_description = [];
            $this->field_image = [];

            foreach ($this->columns as $column) {
                if (FHtml::field_exists($column, 'attribute') && $column->attribute) {
                    $this->field_name[] = $column['attribute'];
                }
            }
            $this->layout = '{toolbar}{items}';
            parent::init();
            return;
        }

        $listView = self::initListView();
        if (isset($listView)) {
            $this->initToggleData();

            $this->initExport();
            if ($this->export !== false && isset($this->exportConfig[self::PDF])) {
                Config::checkDependency(
                    'mpdf\Pdf',
                    'yii2-mpdf',
                    "for PDF export functionality. To include PDF export, follow the install steps below. If you do not " .
                    "need PDF export functionality, do not include 'PDF' as a format in the 'export' property. You can " .
                    "otherwise set 'export' to 'false' to disable all export functionality"
                );
            }
            $this->initHeader();
            $this->initBootstrapStyle();
            $this->containerOptions['id'] = $this->options['id'] . '-container';
            Html::addCssClass($this->containerOptions, 'kv-grid-container');
            $this->registerAssets();
            $this->renderPanel();
            $this->initLayout();
            $this->beginPjax();
            $listView->layout = $this->layout;
            $listView->toolbar = $this->toolbar;

            if (!empty($this->field_name))
                $listView->field_name = $this->field_name;
            if (!empty($this->field_description))
                $listView->field_description = $this->field_description;
            if (!empty($this->field_group))
                $listView->field_group = $this->field_group;
            if (!empty($this->field_user))
                $listView->field_user = $this->field_user;
            if (!empty($this->field_id))
                $listView->field_id = $this->field_id;
            if (!empty($this->field_business))
                $listView->field_business = $this->field_business;
            if (!empty($this->field_count))
                $listView->field_count = $this->field_count;
            if (!empty($this->itemView))
                $listView->itemView = $this->itemView;

            $listView->run();

            $this->endPjax();
        }
        else {
            parent::run();
        }
    }

    protected function initSorting() {
        if ($this->sort_enabled && FHtml::field_exists($this->object_type, 'sort_order')) {
            if (empty($this->orderUrl))
                $this->orderUrl = '/api/utility/sort-order';

            $classes = isset($this->options['class']) ? $this->options['class'] : '';
            $classes .= ' sortable';
            $this->options['class'] = trim($classes);

            $view = $this->getView();
            JuiAsset::register($view);

            $url = Url::toRoute($this->orderUrl);
            $id = $this->getId();

            $sortOpts = array_merge($this->sortOptions, [
                'helper' => new JsExpression('function(e, ui) {
                ui.children().each(function() {
                   $(this).width($(this).width());
                });
                return ui;
            }'),
                'update' => new JsExpression("function(e, ui) {
                jQuery('#{$this->id}').addClass('sorting');
                result = [];
                $('#{$id} tbody').children().each(function( index, element ) { result.push($(this).attr('data-key')); });
                //alert(ui.item.data('key') + ':' + ui.item.index() + ':' + result);
                //alert('{$url}?object_type={$this->object_type}&sort_orders=' + result);
                jQuery.ajax({
                    type: 'POST',
                    url: '{$url}?object_type={$this->object_type}&sort_orders=' + result,
                    data: {
                        key: ui.item.data('key'),
                        pos: ui.item.index()
                    },
                    success: function (data) {
                        $.pjax.reload('#{$this->id}', {timeout : false})
                    },
                    complete: function() {
                        jQuery('#{$this->id}').removeClass('sorting');
                    }
                });
            }")
            ]);

            if ($this->sortAxis) $sortOpts['axis'] = $this->sortAxis;

            $sortJson = Json::encode($sortOpts);

            $view->registerJs("jQuery('#{$id} tbody').sortable($sortJson);");
        }
    }

    protected function initListView() {
        $listtype = str_replace('_', '', $this->display_type);

        $listView = null;
        if ($listtype == FListView::VIEW_GRID_BIG) {
            $listView = new FListView([
                'id' => $this->id,
                'dataProvider' => $this->dataProvider,
                'display_type' => \common\widgets\FListView::VIEW_GRID,
                'toolbar' => self::renderToolbar(),
                'itemSize' => 3
            ]);
        } else if ($listtype == FListView::VIEW_GRID_SMALL) {
            $listView = new FListView([
                'id' => $this->id,
                'dataProvider' => $this->dataProvider,
                'display_type' => \common\widgets\FListView::VIEW_GRID,
                'toolbar' => self::renderToolbar(),
                'itemSize' => 2
            ]);
        }
        else if (!empty($this->display_type) && !FHtml::isInArray($this->display_type, ['print', FHtml::DISPLAY_TYPE_WIDGET])) {
            $listView = new FListView([
                'id' => $this->id,
                'dataProvider' => $this->dataProvider,
                'display_type' => $this->display_type,
                'toolbar' => self::renderToolbar(),
            ]);
        }

        return $listView;
    }

    protected function initColumns()
    {
        //2017/3/6
        if (is_string($this->columns[0]))
        {
            $cells[] = [
                'class' => 'kartik\grid\SerialColumn',
                'width' => '30px',
            ];

            foreach ($this->columns as $column) {
                $arr = FHtml::parseAttribute($column);
                $cells[] = [
                    'class' => FHtml::COLUMN_VIEW,
                    'attribute' => $arr['attribute'],
                    'format' => $arr['format'],
                    'value' => function ($model, $key, $index, $column1) {
                        $field = $column1->attribute;
                        $value = FHtml::getFieldValue($model, $field);

                        $result = FHtml::showContent($value, '', $this->object_type, $field, '', '', '', '');

                        return $result;
                    },
                    'label' => $arr['label']
                ];
            }

            if ($this->display_type != FHtml::DISPLAY_TYPE_WIDGET) {
                $cells[] = [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false, // Dropdown or Buttons
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '80px',
                    'urlCreator' => function ($action, $model) {
                        return Url::to([$action, 'id' => $model->id, 'form_type' => FHtml::getRequestParam('form_type')]);
                    },
                    'visibleButtons' => [
                        'update' => FHtml::isInRole($this->object_type, 'update'),
                        'delete' => FHtml::isInRole($this->object_type, 'delete'),
                    ],
                    'viewOptions' => ['role' => $this->getView()->params['displayType'], 'title' => FHtml::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
                    'updateOptions' => ['role' => $this->getView()->params['editType'], 'title' => FHtml::t('common', 'title.update'), 'data-toggle' => 'tooltip'],
                ];
            } else {

                $cells[] = [
                    'class' => 'common\widgets\FActionColumn',
                    'dropdown' => 'ajax', // Dropdown or Buttons
                    'actionLayout' => $this->actionLayout,
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '120px'
                ];
            }
            $this->columns = $cells;
        } else {
            if ($this->display_type == FHtml::DISPLAY_TYPE_WIDGET) {
                $cells = [];
                foreach ($this->columns as $column) {
                    if (FHtml::field_exists($column, 'class') && StringHelper::endsWith($column['class'], 'ActionColumn')) {
                        $cells[] = [
                            'class' => 'common\widgets\FActionColumn',
                            'dropdown' => 'ajax', // Dropdown or Buttons
                            'actionLayout' => $this->actionLayout,
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'width' => '120px'
                        ];
                    } else {
                        $cells[] = $column;
                    }
                }
                $this->columns = $cells;
            }
        }

        if (empty($this->object_type))
            $this->object_type = FHtml::getTableName($this->filterModel);

        if ((FHtml::settingDynamicGrid() || $this->render_type == FHtml::RENDER_TYPE_DB_SETTING)) {
            $this->columns = FHtml::buildGridColumns($this->object_type, $this->columns); // load columns from db
        }

//        if (FHtml::isInArray($this->object_type, FHtml::TABLES_COMMON)) {
//            $this->render_type = FHtml::RENDER_TYPE_CODE;
//        }

        //2017/3/5
        if ($this->display_type == 'print') {
            $this->render_type = FHtml::RENDER_TYPE_CODE;
            $this->edit_type = FHtml::EDIT_TYPE_VIEW;
            $columns = [];
            foreach ($this->columns as $i => $column) {
                if (FHtml::field_exists($column, 'class') && $column['class'] == FHtml::COLUMN_EDIT)
                    $column['class'] = FHtml::COLUMN_VIEW;
                unset($column['editableOptions']);

                if (FHtml::isInArray($column['class'], ['*DataColumn', '*BooleanColumn']) && (FHtml::field_exists($column, 'attribute') && !FHtml::isInArray($column['attribute'], FHtml::FIELDS_HIDDEN)))
                    $columns[] = $column;
            }
            $this->bordered = true;
            $this->condensed = true;
            $this->columns = $columns;
        }

        foreach ($this->columns as $i => $column) {
            $edit_type = FHtml::getFieldValue($column, 'edit_type', $this->edit_type);
            if (empty($edit_type))
                $edit_type = $this->edit_type;

            $class = FHtml::getFieldValue($column, 'class', '');
            if (key_exists('edit_type', $column)) {
                unset($column['edit_type']); // remove key 'edit_type' because it is custom key, not allowed in DataColumn
            }

            if (key_exists('readonly', $column)) {
                unset($column['readonly']); // remove key 'edit_type' because it is custom key, not allowed in DataColumn
                $edit_type = FHtml::EDIT_TYPE_VIEW;
            }

            if (!key_exists('class', $column)) {
                $column['class'] = FHtml::COLUMN_VIEW;
            }


            //2017/03/28
            if (FHtml::isInArray($class, ['*SerialColumn', '*CheckboxColumn', '*ExpandRowColumn'])) {
                $column['hAlign'] = 'center';
                $column['vAlign'] = 'middle';
                $this->columns[$i] = $column;
                continue;
            }
            else if ($edit_type == FHtml::EDIT_TYPE_DEFAULT || $this->render_type == FHtml::RENDER_TYPE_CODE || FHtml::isInArray($column['class'], ['*DataColumn', '*ActionColumn'])) {
                $this->columns[$i] = $column;
                continue;
            }

            $field = FHtml::field_exists($column, 'attribute') ? $column['attribute'] : '';

            $canEdit = true;
            if (!FHtml::isEditInGrid($this->object_type, $field)) {
                $edit_type = FHtml::EDIT_TYPE_VIEW;
                $canEdit = false;
            }

            if ($edit_type == FHtml::EDIT_TYPE_VIEW) {
                if (FHtml::isInArray($column['class'], ['*DataColumn','*EditableColumn', '*BooleanColumn'])) {
                    $column['class'] = FHtml::COLUMN_VIEW;
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $showType = FHtml::field_exists($column1, 'format') && !empty($column1->format) && !FHtml::isInArray($column1->format, ['raw']) ? $column1->format : FHtml::getShowType($model, $column1->attribute);
                        $field = $column1->attribute;
                        $value = FHtml::getFieldValue($model, $field);
                        $result = FHtml::showContent($value, $showType, $this->object_type, $field, '', '', '', '');
                        return $result;
                    };
                    $column['format'] = 'raw';
                }
            } else if (!empty($field) && StringHelper::endsWith($class, 'BooleanColumn') || FHtml::isInArray($field, FHtml::FIELDS_BOOLEAN, $this->object_type)) {
                $column['hAlign'] = 'center';
                $column['vAlign'] = 'middle';
                $column['format'] = 'raw';

                $column['filterType'] = GridView::FILTER_SELECT2;
                $column['filter'] = FHtml::getComboArray('', $this->object_type, $field, true, 'id', 'name');
                $column['filterWidgetOptions'] = [
                    'pluginOptions' => ['allowClear' => true],
                ];
                $column['class'] = FHtml::COLUMN_VIEW;

                if ($edit_type == FHtml::EDIT_TYPE_INLINE) {

                    $column['value'] = function ($model, $key, $index, $column1) {
                        $field = $column1->attribute;

                        $result = FHtml::showModelFieldValue($model, $field);

                        return $result;
                    };
                } else if ($edit_type == FHtml::EDIT_TYPE_INPUT) {
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $result = Html::activeCheckbox($model, $column1->attribute,
                            ['style' => 'color:lightgrey', 'class' => 'editable-' . $column1->attribute, 'object_type' => $this->object_type, 'model_id' => $model->id, 'model_field' => $column1->attribute]);
                        return $result;
                    };

                    FHtml::registerEditorJS($field, $edit_type, $this->id);

                } else if ($edit_type == FHtml::EDIT_TYPE_POPUP) {
                    $column['class'] = FHtml::COLUMN_EDIT;
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $field = $column1->attribute;
                        $value = FHtml::getFieldValue($model, $field);

                        $result = FHtml::showActive($value, $field);
                        return $result;
                    };
                    $column['editableOptions'] = [
                        'size' => 'md',
                        'inputType' => \kartik\editable\Editable::INPUT_SWITCH,
                        'options' => [
                            // 'data' => FHtml::getComboArray('', $this->object_type, $column['attribute'], true, 'id', 'name')
                        ]
                    ];
                }

            } else if (!empty($field) && (FHtml::isInArray($field, FHtml::getFIELDS_GROUP(), $this->object_type) || key_exists($this->object_type . '.' . $field, FHtml::LOOKUP))) {
                $column['class'] = FHtml::COLUMN_VIEW;
                $column['hAlign'] = 'center';
                $column['vAlign'] = 'middle';
                $column['format'] = 'raw';
                $column['filterInputOptions'] = ['placeholder' => ''];
                $column['filterWidgetOptions'] = [
                    'pluginOptions' => ['allowClear' => true],
                ];

                if ($edit_type == FHtml::EDIT_TYPE_INLINE) {
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $field = $column1->attribute;
                        //2017/3/6
                        $result = FHtml::showModelFieldValue($model, $field);
                        return $result;
                    };
                    // FHtml::registerEditorJS($field, $edit_type, $this->id);
                } else if ($edit_type == FHtml::EDIT_TYPE_INPUT) {
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $data = FHtml::getComboArray('', $this->object_type, $column1->attribute, true, 'id', 'name');

                        $result = Html::activeDropDownList($model, $column1->attribute,
                            $data, ['class' => 'form-control editable-' . $column1->attribute, 'object_type' => $this->object_type, 'model_id' => $model->id, 'model_field' => $column1->attribute]);
                        return $result;
                    };

                    FHtml::registerEditorJS($field, $edit_type, $this->id);

                } else if ($edit_type == FHtml::EDIT_TYPE_POPUP) {
                    $column['class'] = FHtml::COLUMN_EDIT;
                    $column['editableOptions'] = [
                        'size' => 'md',
                        'inputType' => \kartik\editable\Editable::INPUT_SELECT2,
                        'options' => [
                            'data' => FHtml::getComboArray('', $this->object_type, $column['attribute'], true, 'id', 'name')
                        ]
                    ];
                }
            } else if (!empty($field) && !FHtml::isInArray($field, FHtml::FIELDS_IMAGES, $this->object_type)) {
                $column['class'] = FHtml::COLUMN_VIEW; //FHtml::getColumnClass(FHtml::currentController(), $column['attribute'], '');
                $column['hAlign'] = 'left';
                $column['vAlign'] = 'middle';
                $column['format'] = 'raw';

                if ($edit_type == FHtml::EDIT_TYPE_INLINE) {
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $field = $column1->attribute;
                        $id = FHtml::getFieldValue($model, ['id', 'product_id']);
                        $value = FHtml::getFieldValue($model, $field);
                        $showType = FHtml::getShowType($model, $field);

                        $result = FHtml::showContent($value, $showType, $this->object_type, $field);
                        $result = FHtml::showContentEditable($result, $value, $field, $id, $this->object_type);
                        return $result;
                    };

                    FHtml::registerEditorJS($field, $edit_type, $this->id);
                } else if ($edit_type == FHtml::EDIT_TYPE_INPUT) {
                    $column['value'] = function ($model, $key, $index, $column1) {
                        $result = Html::activeTextarea($model, $column1->attribute,
                            ['class' => 'form-control editable-' . $column1->attribute, 'object_type' => $this->object_type, 'model_id' => $model->id, 'model_field' => $column1->attribute]);
                        return $result;
                    };

                    FHtml::registerEditorJS($field, $edit_type, $this->id);

                } else if ($edit_type == FHtml::EDIT_TYPE_POPUP) {
                    $column['class'] = FHtml::COLUMN_EDIT;
                    $column['editableOptions'] = [
                        'size' => 'md',
                        'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
                        'options' => [

                        ]
                    ];
                }
            }

            $this->columns[$i] = $column;
        }


        parent::initColumns(); // TODO: Change the autogenerated stub
        $columns = [];
        foreach ($this->columns as $i => $column) {
//            if ($this->form_enabled && FHtml::field_exists($column, 'attribute') && $column->group == true)
//                $column->groupedRow = false;

            if (FHtml::field_exists($column, 'attribute') && $column->attribute == 'id') {
                $column->visible = false;
                $column->width = '2%';
                $this->columns[$i] = $column;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, FHtml::FIELDS_IMAGES, $this->object_type)) {
                $column->visible = false;
                $column->width = '5%';
                $this->columns[$i] = $column;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, FHtml::FIELDS_BOOLEAN, $this->object_type)) {
                $column->visible = false;
                $column->width = '8%';
                $this->columns[$i] = $column;

                $columns[] = $column->attribute;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, FHtml::getFIELDS_GROUP(), $this->object_type)) {
                $column->visible = false;
                $column->width = '10%';
                $this->columns[$i] = $column;

                $columns[] = $column->attribute;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, ['*color', '*icon'])) {
                $column->visible = false;
                $column->width = '5%';
                $this->columns[$i] = $column;
            } else if (FHtml::field_exists($column, 'attribute') && FHtml::isInArray($column->attribute, FHtml::FIELDS_PRICE, $this->object_type)) {
                $column->visible = false;
                $column->width = '10%';
                $this->columns[$i] = $column;

                $columns[] = $column->attribute;
            } else if (StringHelper::endsWith(get_class($column), 'ActionColumn')) {
                $column->header = '';
                $column->visible = false;
                $column->width = '8%';

                $this->columns[$i] = $column;
            }  else if (StringHelper::endsWith(get_class($column), 'SerialColumn')) {
                $column->visible = false;
                $column->width = '1%';
                $this->columns[$i] = $column;
            } else if (StringHelper::endsWith(get_class($column), 'CheckboxColumn')) {
                $column->visible = false;
                $column->width = '1%';
                $this->columns[$i] = $column;
            } else if (StringHelper::endsWith(get_class($column), 'ExpandRowColumn')) {
                $column->visible = false;
                $column->width = '1%';
                $this->columns[$i] = $column;
            } else {
                if (FHtml::field_exists($column, 'attribute'))
                {
                    $columns[] = $column->attribute;
                }
            }
        }

        if ($this->filterEnabled !== true) { //turn off Filter bar
            $this->filterModel = null;
        } else {
            $this->form_field_template = '{object}[{column}]'; // both Filter/Search and Add form existed -> change naming method
        }

        if ($this->form_enabled == true) {
            if (is_string($this->form_fields) && empty($this->form_fields)) {
                $this->toolbar = $this->getView()->render($this->form_fields, ['model' => $this->filterModel, 'modelMeta' => null]);
            } else if (is_array($this->form_fields)) {
                if (!empty($this->form_fields)) { //predefined form_fields
                    FHtml::registerPlusJS($this->object_type, $this->form_fields, $this->id, $this->form_field_template, $this->default_fields);
                    $this->toolbar = FHtml::showPlusForms($this->object_type, $this->form_fields, $this->id, $this->form_field_template); // replate Toolbar
                } else { // auto form_fields
                    $this->filterModel = null; //turn off filter
                    $this->form_field_template = '{object}Search[{column}]';
                    FHtml::registerPlusJS($this->object_type, $columns, $this->id, $this->form_field_template, $this->default_fields);
                }
            }
        }
    }

    public function renderFilters()
    {
        if ($this->display_type == 'print')
            return '';

        $tr_filter = !isset($this->filterModel) ? '' : parent::renderFilters();

        $tr_plus = '';
        if (($this->form_enabled == true && empty($this->form_fields)) && $this->filterEnabled === true && !isset($this->filterModel)) {
            $cells = [];
            foreach ($this->columns as $column) {
                $class = get_class($column);
                /* @var $column Column */
                if (StringHelper::endsWith(get_class($column), 'ActionColumn')) {
                    $cells[] = 'action';
                } else if (FHtml::field_exists($column, 'attribute')) {
                    $cells[] = $column->attribute;
                } else if (FHtml::field_exists($column, 'attribute') && $column->group == true && $column->groupedRow == true) {
                    $cells[] = 'group';
                } else {
                    $cells[] = 'null';
                }
            }

            $tr_plus .= FHtml::showPlusTableRow($this->object_type, $cells, $this->id, $this->form_field_template, $this->display_type !== FHtml::DISPLAY_TYPE_WIDGET);
        }

        return $tr_filter . $tr_plus;
    }

    //2017/3/5
    protected function renderToolbar($populateAll = false)
    {
        $obj_name = FHtml::t('common', BaseInflector::camel2words($this->object_type));
        if ($this->display_type == 'print')
            return FHtml::showPrintHeader($obj_name);

        $result = parent::renderToolbar();
        if (!empty($this->display_type))
        {
            $result .= '<table class="table">'  . $this->renderTableHeader().  '</table>';
            //FHtml::registerPlusJS($this->object_type, $this->form_fields, $this->id);
        }

        return $result;
    }

    //2017/3/5
    public function renderTableHeader()
    {
        if ($this->display_type == 'print') {
            $cells = [];

            foreach ($this->columns as $index => $column) {
                $cells[] = '<td class="text-center">' . (FHtml::field_exists($column, 'attribute') ? FHtml::getFieldLabel($this->object_type, $column->attribute) : '') . '</td>';
            }
            $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);

            return "<thead>\n" .
            $content . "\n" .
            "</thead>";
        } else
        {
            return parent::renderTableHeader();
        }
    }

    public function renderToggleData()
    {
        $result = '';
        if (!$this->toggleData) {
            $result = '';
        }
        $maxCount = ArrayHelper::getValue($this->toggleDataOptions, 'maxCount', false);
        if ($maxCount !== true && (!$maxCount || (int)$maxCount <= $this->dataProvider->getTotalCount())) {
            $result = '';
        }
        $result .= parent::renderToggleData();

        $options = $this->toggleDataOptions['all'];
        $form_enabled = FHtml::getRequestParam('form_enabled', false);
        $label = $form_enabled ? '<span class="glyphicon glyphicon-search"></span>' : '<span class="glyphicon glyphicon-plus"></span>';

        $url = Url::current(['form_enabled' => !$form_enabled]);
        static::initCss($this->toggleDataContainer, 'btn-group');
        $result .= Html::tag('div', Html::a($label, $url, $options), $this->toggleDataContainer);
        return $result;
    }
}
