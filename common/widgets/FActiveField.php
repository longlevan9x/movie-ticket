<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use common\components\FHtml;
use common\widgets\BaseWidget;
use common\widgets\formfield\FieldEdit;
use iutbay\yii2kcfinder\KCFinderAsset;
use kartik\date\DatePicker;
use kartik\form\ActiveField;
use kartik\form\ActiveForm;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Slider;

class FActiveField extends ActiveField
{
    public $column;
    public $label_span = 3;
    public $display_type;
    public $edit_type;
    public $showType;
    public $dbType;
    public $object_type;
    public $appendContent;
    public $prependContent;
    public $labelHint;

    public function labelSpan($span) {
        $this->label_span = $span;
        return $this;
    }

    public function label($label = null, $options = []) {
        if ($label === false || $label === null)
            $this->label_span = false;

        if (is_numeric($label)) {
            if ($label == 12) {
                $label = 3;
                $this->display_type = FActiveForm::TYPE_INLINE;
            }
            $this->label_span = $label;
        }

        if (is_numeric($options)) {
            $this->label_span = $options;
            $options = [];
        }

        return parent::label($label, $options);
    }

    public function displayType($type) {
        $this->display_type = $type;
        return $this;
    }

    protected $isSettingField = false;

    public function hiddenInput($options = [])
    {
        $this->label_span = -1;
        $this->parts['{input}'] = Html::activeHiddenInput($this->model, $this->attribute, $options);
        $this->parts['{label}'] = '';
        return $this;
    }

    public function render($content = null)
    {
        if ($this->label_span == -1) //hidden Input
            return $this->parts['{input}'];

        if (FHtml::isDynamicFormEnabled()) {
            $canEdit = true;
            $moduleKey = str_replace('-', '_', FHtml::currentController());
            $modulePath = str_replace('_', '-', $moduleKey);

            if (!isset($this->column)) {
                if (method_exists($this->model, 'getColumn')) {
                    $column = $this->model->getColumn($this->attribute);
                    if (isset($column)) {
                        $this->column = $column;
                    }
                }
            }

            if ($this->isHidden())
                return '';

            if (isset($this->column)) {
                $editor = $this->column->editor;
                $lookup = $this->column->lookup;

                if (self::isReadOnly()) {
                    $showType = '';
                    $this->parts['{input}'] = '<b>' . FHtml::showModelFieldValue($this->model, $this->attribute, $showType) . '</b>';
                } else if (!empty($editor)) {
                    $this->parts['{input}'] = FHtml::buildEditor1($this->model, $this->attribute, $this->form, $editor, $lookup, $this);
                }
                if (!empty($this->column->description))
                    $this->hint($this->column->description);
            }
        }

        if (empty($this->display_type))
            $this->display_type = $this->form->type;

        if ($this->edit_type == FHtml::EDIT_TYPE_INLINE) {
            $this->parts['{input}'] = FHtml::showModelField($this->model, $this->attribute, $this->showType, FHtml::LAYOUT_NO_LABEL, '');
        } else if ($this->edit_type == FHtml::EDIT_TYPE_VIEW) {
            $this->parts['{input}'] = '<div style="padding-top:10px">' . FHtml::showModelFieldValue($this->model, $this->attribute, $this->showType, FHtml::LAYOUT_NO_LABEL, '') . '</div>';
        }

        if (!empty($this->appendContent))
            $this->parts['{input}'] .= $this->appendContent;

        if (!empty($this->prependContent))
            $this->parts['{input}'] = $this->prependContent . $this->parts['{input}'];


        if ($this->label_span === false) {
            $this->parts['{label}'] = '';
            $style = ($this->edit_type == FHtml::EDIT_TYPE_INLINE) ? 'margin-top:10px;' : '';

            $this->parts['{input}'] = "<div class='col-md-12' style='{$style} margin-bottom: 10px'><div style='width:100%'>" . $this->parts['{input}'] . '</div></div>';
        } else {
            if ($this->form->type == FActiveForm::TYPE_VERTICAL) {
                $this->label_span = 12;
                $this->template = '{label}{input}';
                $this->parts['{label}'] = "<div class='col-md-12' style='margin-bottom: 10px'><div class='row'><div class='no-spacing form-label' style='padding-bottom:5px'>" . FHtml::getFieldLabel($this->model, $this->attribute, $this->isSettingField) . $this->labelHint  . '</div>';
                $span = 12;
                $style = ($this->edit_type == FHtml::EDIT_TYPE_INLINE) ? 'margin-top:10px;' : '';
                $this->parts['{input}'] = "" . $this->parts['{input}'] . '</div></div>';
            }  else if ($this->form->type == FActiveForm::TYPE_HORIZONTAL) {

                if (!isset($this->label_span)) {
                    $this->label_span = 3;
                }
                $this->parts['{label}'] = "<div class='col-md-12' style='margin-bottom: 10px'><div class='row'><div class='col-md-{$this->label_span} no-spacing form-label' style='padding:10px'>" . FHtml::getFieldLabel($this->model, $this->attribute, $this->isSettingField) . $this->labelHint . '</div>';
                $span = $this->form->fullSpan - $this->label_span;
                $style = ($this->edit_type == FHtml::EDIT_TYPE_INLINE) ? 'margin-top:10px;' : '';
                $this->parts['{input}'] = "<div class='col-md-{$span}' style='{$style}'>" . $this->parts['{input}'] . '</div></div></div>';
            }
        }

        return parent::render($content);
    }

    public function isHidden() {
        if (isset($this->column))
        {
            if ($this->column->is_active == false)
                return true;
        }

        return false;
    }

    public function isReadOnly() {
        if (isset($this->column))
        {
            if ($this->column->is_readonly == 1)
                return true;
        }

        return false;
    }

    //2017/3/21
    public function lookup($object_type, $options = [], $populated_fields = [], $search_field = 'name', $id_field = 'id', $class = FHtml::EDITOR_SELECT) {
        $this->isSettingField = true;
        if (is_string($options)) {
            $options = [];
        }
        $id = FHtml::getFieldValue($this->model, $this->attribute);
        $item = FHtml::getModel($object_type, '', $id, null, false);
        $items = [$id => FHtml::getFieldValue($item, $search_field)];
        return self::select($items, $options, $object_type, $populated_fields, $search_field, $id_field, $class);
    }

    //2017.4.18
    public function select($items = null, $options = [], $lookup_object = '', $populated_fields = [], $search_field = 'name', $id_field = 'id', $class = FHtml::EDITOR_SELECT)
    {
        $this->isSettingField = true;

        if (is_string($items))
        {
            $items = null;
            $this->object_type = $items;
        }

        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);

        $this->object_type = FHtml::getTableName($this->model);

        if (!isset($items))
            $items = FHtml::getComboArray($this->attribute, $this->object_type, $this->attribute, true);

        if (!empty($lookup_object))  {
            /* @var $class \yii\base\Widget */
            $config['model'] = $this->model;
            $config['attribute'] = $this->attribute;
            $config['view'] = $this->form->getView();
            $config['data'] = $items;
            $config['options'] = $options;
            $config['pluginOptions'] = ['allowClear' => true, 'tags' => true, 'multiple' => false];

            $config = array_merge($config, FHtml::getSelect2Options($this->object_type, $populated_fields, $lookup_object, $search_field, $id_field));
            if (key_exists('pluginOptions', $config))
                $config['pluginOptions'] = array_merge($config['pluginOptions'], ['allowClear' => true, 'tags' => true, 'multiple' => false]);
            $this->parts['{input}'] = $class::widget($config);
        } else {
            parent::dropDownList($items);
        }


        return $this;
    }

    public function selectLookup($object_type, $options = [], $populated_fields = [], $search_field = 'name', $id_field = 'id') {
        return self::select(null, $options, $object_type, $populated_fields, $search_field, $id_field, FHtml::EDITOR_SELECT);
    }

    public function selectMany($items = null, $options = [], $lookup_object = '', $populated_fields = [], $search_field = 'name', $id_field = 'id', $class = FHtml::EDITOR_SELECT)
    {
        $this->isSettingField = true;

        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();
        $this->object_type = FHtml::getTableName($this->model);

        if (!isset($items))
            $items = FHtml::getComboArray($this->attribute, $this->object_type, $this->attribute, true);

        $config['data'] = $items;
        $config['options'] = $options;

        $config['pluginOptions'] = ['allowClear' => true, 'tags' => true, 'multiple' => true];

        if (!empty($lookup_object))  {
            $config = array_merge($config, FHtml::getSelect2Options($this->object_type, $populated_fields, $lookup_object, $search_field, $id_field));
            if (key_exists('pluginOptions', $config))
                $config['pluginOptions'] = array_merge($config['pluginOptions'], ['allowClear' => true, 'tags' => true, 'multiple' => true]);
        }

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function selectManyLookup($object_type, $options = [], $populated_fields = [], $search_field = 'name', $id_field = 'id') {
        return self::selectMany(null, $options, $object_type, $populated_fields, $search_field, $id_field, FHtml::EDITOR_SELECT);
    }

    public function selectMultiple($items = null, $options = [], $lookup_object = '', $populated_fields = [], $search_field = 'name', $id_field = 'id', $class = FHtml::EDITOR_SELECT)
    {
        return self::selectMany($items, $options, $lookup_object, $populated_fields, $search_field, $id_field, $class);
    }

    public function dropDownList($items = null, $options = [], $lookup_object = '', $populated_fields = [], $search_field = 'name', $id_field = 'id')
    {
        return self::select($items, $options, $lookup_object, $populated_fields, $search_field, $id_field, FHtml::EDITOR_SELECT);
    }

    public function dateInput($format = 'yyyy-mm-dd', $options = [], $disabled_date = '', $disabled_hours = '', $class = 'common\widgets\FDatePicker')
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();

        $config['options'] = $options;

        $config['pluginOptions'] = ['convertFormat' => true, 'format' => $format, 'class' => 'form-control', 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true, 'daysOfWeekDisabled' => $disabled_date, 'hoursDisabled' => $disabled_hours ];

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function date($format = 'yyyy-mm-dd', $options = [], $disabled_date = '', $disabled_hours = '', $class = FHtml::EDITOR_DATE)
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();

        $config['options'] = $options;

        $config['pluginOptions'] = ['convertFormat' => true, 'format' => $format, 'class' => 'form-control', 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true, 'daysOfWeekDisabled' => $disabled_date, 'hoursDisabled' => $disabled_hours ];

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function datetime($format = 'yyyy-mm-dd hh:ii', $options = [], $disabled_date = '', $disabled_hours = '',  $class = FHtml::EDITOR_DATETIME)
    {
        return self::date($format, $options, $disabled_date, $disabled_hours, $class);
    }

    public function html($options = ['rows' => 5, 'disabled' => false], $preset = 'default', $class = FHtml::EDITOR_HTML)
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();

        $config['options'] = $options;

        $config['preset'] = $preset;

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function fckeditor($options = ['rows' => 5, 'disabled' => false], $preset = 'basic', $class = FHtml::EDITOR_HTML)
    {
       return self::html($options, $preset, $class);
    }

    public function checkbox($options = [], $class = FHtml::EDITOR_SWITCH)
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();

        $config['options'] = $options;

        $this->parts['{input}'] = '<div class="" style="padding-left:15px">' . $class::widget($config) . '</div>';

        return $this;
    }

    public function boolean($options = [], $class = FHtml::EDITOR_SWITCH)
    {
        return self::checkbox($options, $class);
    }

    public function fileInput($options = [], $accept = '', $max = null, $class = FHtml::EDITOR_FILE)
    {
        if (empty($accept))
            $accept = FHtml::settingAcceptedFileType();
        if (empty($max))
            $max = FHtml::settingMaxFileSize();

        $options = array_merge($this->inputOptions, $options);
        $options = array_merge($options, ['accept' => $accept, 'multiple' => false]);

        $this->adjustLabelFor($options);

        /* @var $class \yii\base\Widget */
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();

        $config['options'] = $options;
        $config['pluginOptions'] = ['maxFileSize' => $max, 'showPreview' => true, 'showCaption' => false, 'showRemove' => true,'showUpload' => false, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any']];

        $this->parts['{input}'] = $class::widget($config);

        return $this;
    }

    public function image($options = [], $accept = '', $max = null, $class = FHtml::EDITOR_FILE)
    {
        return self::fileInput($options, $accept, $max, $class);
    }

    public function file($options = [], $accept = '', $max = null, $class = FHtml::EDITOR_FILE)
    {
        return self::fileInput($options, $accept, $max, $class);
    }

    public function numeric($options = [], $autoGroup = false, $class = FHtml::EDITOR_NUMERIC)
    {
        $options = array_merge($options, ['clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => $autoGroup, 'removeMaskOnSubmit' => true]]);

        return self::widget($class, $options);
    }

    public function time($options = [], $autoGroup = false, $class = FHtml::EDITOR_NUMERIC)
    {
        $options = array_merge($options, ['clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => $autoGroup, 'removeMaskOnSubmit' => true]]);

        return self::widget($class, $options);
    }

    public function numericInput($options = [], $autoGroup = false,  $class = FHtml::EDITOR_NUMERIC)
    {
        return self::numeric($options, $autoGroup, $class);
    }

    public function currency($prefix = '', $options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        if (empty($prefix))
            $prefix = FHtml::settingCurrency();

        $options = array_merge($options, ['clientOptions' => ['prefix' => $prefix . ' ', 'alias' =>  'currency', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]);
        return self::widget($class, $options);
    }

    public function currencyInput($prefix = '', $options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::currency($prefix, $options, $class);
    }

    public function moneyInput($prefix = '', $options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::currency($prefix, $options, $class);
    }

    public function money($prefix = '', $options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::currency($prefix, $options, $class);
    }

    public function emailInput($options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        $options = array_merge($options, ['clientOptions' => ['alias' =>  'email']]);
        return self::textInput($options);
    }

    public function email($options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::emailInput($options, $class);
    }

    public function urlInput($options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        $options = array_merge($options, ['clientOptions' => ['alias' =>  'url']]);
        return self::currency($options, $class);
    }

    public function url($options = [], $class = FHtml::EDITOR_NUMERIC)
    {
        return self::urlInput($options, $class);
    }

    public function maskedInput($masked = '', $class = FHtml::EDITOR_NUMERIC)
    {
        $options = ['mask' => $masked];
        return self::maskedInput($class, $options);
    }

    public function rate($count = 5, $min = 0, $max = 5, $step = 1, $starCaptions = [], $class = FHtml::EDITOR_RATE)
    {
        $options = ['pluginOptions' => [ 'stars' => $count, 'min' => $min, 'max' => $max, 'step' => $step, 'showClear' => true, 'showCaption' => true, 'defaultCaption' => '{rating}', 'starCaptions' => $starCaptions]];
        return self::widget($class, $options);
    }

    public function star($count = 5, $min = 0, $max = 5, $step = 1, $starCaptions = [], $class = FHtml::EDITOR_RATE)
    {
       return self::rate($count, $min, $max, $step, $starCaptions, $class);
    }

    public function slide($min = 0, $max = 100, $step = 1, $class = FHtml::EDITOR_SLIDE)
    {
        $options = ['sliderColor'=> 'grey', 'handleColor'=> 'danger', 'pluginOptions'=>['min'=>$min,'max'=>$max,'step'=>15]];
        return self::widget($class, $options);
    }

    public function progress($min = 0, $max = 100, $step = 1, $class = FHtml::EDITOR_SLIDE)
    {
        $options = ['sliderColor'=> 'grey', 'handleColor'=> 'danger', 'pluginOptions'=>['min'=>$min,'max'=>$max,'step'=>15]];
        return self::widget($class, $options);
    }

    public function color($class = FHtml::EDITOR_COLOR)
    {
        $options = [];
        return self::widget($class, $options);
    }

    public function staticInput($options = [])
    {
        $content = isset($this->staticValue) ? $this->staticValue :
            FHtml::showModelFieldValue($this->model, $this->attribute, 'readonly');

        Html::addCssClass($options, 'form-control-static');
        $this->parts['{input}'] = Html::tag('div', $content, $options);
        $this->_isStatic = true;
        return $this;
    }

    public function readonly($options = [])
    {
        return self::staticInput($options);
    }

    public function inlineInput($options = [])
    {
        $content = FHtml::showModelField($this->model, $this->attribute);
        Html::addCssClass($options, 'form-control-static');
        $this->parts['{input}'] = Html::tag('div', $content, $options);
        $this->_isStatic = false;
        return $this;
    }

    public function inline($options = [])
    {
        return self::inlineInput($options);
    }

    //2017.5.17
    public function hintLabel($content, $layout = '<div class="label-hint" style="color:grey; font-size:80%">{content}</div>') {

        $this->labelHint =  str_replace('{content}', $content, $layout);
        return $this;
    }

    public function appendContent($content, $layout = '<div class="row"><div class="col-md-12">{content}</div></div>') {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $content = str_replace(['{id}', '{attribute}'], [$id, $attribute], $content) ;

        $this->appendContent =  str_replace('{content}', $content, $layout);
        return $this;
    }

    public function prependContent($content, $layout = '<div class="row"><div class="col-md-12">{content}</div></div>') {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $content = str_replace(['{id}', '{attribute}'], [$id, $attribute], $content) ;
        $this->prependContent =  str_replace('{content}', $content, $layout);
        return $this;
    }

    public function renderView($view = '', $params = [], $options = []) {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $formName = BaseInflector::camelize(FHtml::getTableName($this->model));
        $params = array_merge($params, ['model' => $this->model, 'form' => $this->form, 'attribute' => $this->attribute, 'id' => $id, 'formName' => $formName]);
        $this->parts['{input}'] =  FHtml::render($view, '', $params);
        return $this;
    }

    public function appendView($view = '', $params = [], $layout = '<div class="row"><div class="col-md-12">{content}</div></div>') {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $formName = BaseInflector::camelize(FHtml::getTableName($this->model));
        $params = array_merge($params, ['model' => $this->model, 'form' => $this->form, 'attribute' => $this->attribute, 'id' => $id, 'formName' => $formName]);
        $content = FHtml::render($view, '', $params);
        $this->appendContent =  str_replace('{content}', $content, $layout);
        return $this;
    }

    public function prependView($view = '', $params = [], $layout = '<div class="row"><div class="col-md-12">{content}</div></div>') {
        $id = $this->getInputId();
        $attribute = $this->attribute;
        $formName = BaseInflector::camelize(FHtml::getTableName($this->model));
        $params = array_merge($params, ['model' => $this->model, 'form' => $this->form, 'attribute' => $this->attribute, 'id' => $id, 'formName' => $formName]);
        $content = FHtml::render($view, '', $params);
        $this->prependContent =  str_replace('{content}', $content, $layout);
        return $this;
    }
}