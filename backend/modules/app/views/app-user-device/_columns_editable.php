<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "AppUserDevice".
 */

use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$currentRole = FHtml::getCurrentRole();
$moduleName = 'AppUserDevice';
$moduleTitle = 'App User Device';
$moduleKey = 'app-user-device';
$form_type = FHtml::getRequestParam('form_type');

$isEditable = FHtml::isInRole('', 'update');

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('_view_preview', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => false
    ],
    [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'format' => 'html',
        'attribute' => 'id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'id', $form_type, true),
        'value' => function ($model) {
            return '<b>' . FHtml::showContent($model->id, FHtml::SHOW_NUMBER, 'app_user_device', 'id', 'int(11)', 'app-user-device') . '</b>';
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
    ],
    [ //name: user_id, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'user_id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'user_id', $form_type, true),
        'value' => function ($model) {
            return FHtml::showContent($model->user_id, FHtml::SHOW_LABEL, 'app_user_device', 'user_id', 'int(11)', 'app-user-device');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('app_user_device', 'app_user_device', 'user_id', true, 'id', 'name'),
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'editableOptions' => function ($model, $key, $index, $widget) {
            $fields = FHtml::getComboArray('app_user_device', 'app_user_device', 'user_id', true, 'id', 'name');
            return [
                'inputType' => 'dropDownList',
                'displayValueConfig' => $fields,
                'data' => $fields
            ];
        },
    ],
    [ //name: ime, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => FHtml::getColumnClass($moduleName, 'ime', $form_type, true),
        'format' => 'raw',
        'attribute' => 'ime',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'ime', $form_type, true),
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-md-1 nowrap'],
        'editableOptions' => [
            'size' => 'md',
            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
            'widgetClass' => 'kartik\datecontrol\InputControl',
            'options' => [
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ]
        ],
    ],
    [ //name: gcm_id, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'format' => 'raw',
        'attribute' => 'gcm_id',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'gcm_id', $form_type, true),
        'value' => function ($model) {
            return FHtml::showContent($model->gcm_id, FHtml::SHOW_LABEL, 'app_user_device', 'gcm_id', 'varchar(255)', 'app-user-device');
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'width' => '100px',
        'filterType' => GridView::FILTER_SELECT2,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'filter' => FHtml::getComboArray('app_user_device', 'app_user_device', 'gcm_id', true, 'id', 'name'),
    ],
    [ //name: type, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        'class' => 'kartik\grid\BooleanColumn',
        'format' => 'raw',
        'attribute' => 'type',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'type', $form_type, true),
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
    ],
    [ //name: status, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
        'class' => 'kartik\grid\BooleanColumn',
        'format' => 'raw',
        'attribute' => 'status',
        'visible' => FHtml::isVisibleInGrid($moduleName, 'status', $form_type, true),
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '50px',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '80px',
        'urlCreator' => function ($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => $model->id]);
        },
        'visibleButtons' => [
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => $this->params['displayType'], 'title' => FHtml::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['editType'], 'title' => FHtml::t('common', 'title.update'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => FHtml::t('common', 'title.delete'),
            'data-confirm' => false,
            'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => FHtml::t('common', 'Confirmation'),
            'data-confirm-message' => FHtml::t('common', 'messege.confirmdelete')
        ],
    ],
];