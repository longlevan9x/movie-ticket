<?php
use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$colorPluginOptions =  [
'showPalette' => true,
'showPaletteOnly' => true,
'showSelectionPalette' => true,
'showAlpha' => false,
'allowEmpty' => true,
'preferredFormat' => 'name',
'palette' => [
[
"white", "black", "grey", "silver", "gold", "brown",
],
[
"red", "orange", "yellow", "indigo", "maroon", "pink"
],
[
"blue", "green", "violet", "cyan", "magenta", "purple",
],
]
];

$currentRole = FHtml::getCurrentRole();
$moduleName = 'ObjectSetting';

$classControl = '';
$isEditable = true;
$classControl = 'kartik\grid\EditableColumn';
$isEditable = true;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
        'vAlign' => 'top',
    ],
//    [
//        'class' => 'kartik\grid\SerialColumn',
//        'width' => '30px',
//        'vAlign' => 'top',
//    ],
    //[
    //'class'=>'kartik\grid\DataColumn',
    //'attribute'=>'id',
    //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //],
//    [
//
//        'class'=>'kartik\grid\DataColumn',
//        'attribute'=>'object_type',
//        'contentOptions'=>['class'=>'col-md-2 nowrap'],
//    ],

    [
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'meta_key',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'format'=>'html',
        'value' => function($model) { return "<span class='small text-default'>".$model->object_type.":</span><br />".$model->meta_key
            /*. Html::a('&nbsp;&nbsp;<i class="glyphicon glyphicon-pencil"></i>', ['create','object_type' => $model->object_type],
            [
                'role' => '',
                'data-pjax' => false,
            ]) */
            ;},
        'contentOptions'=>['class'=>'col-md-2 nowrap'],
        'group'=> true,  // enable grouping
        'groupedRow' => false,
    ],

    [
        'class'=>'kartik\grid\DataColumn',
        'attribute'=>'key',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'edit_type' => FHtml::EDIT_TYPE_VIEW,

        'contentOptions'=>['class'=>'col-md-2 nowrap'],
    ],
    [
        'class'=>'kartik\grid\EditableColumn',
        'attribute'=>'value',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'edit_type' => FHtml::EDIT_TYPE_INLINE,
        'contentOptions'=>['class'=>'col-md-3 nowrap'],
    ],
    //[
    //'class'=>'kartik\grid\DataColumn',
    //'attribute'=>'description',
    //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //],
    [
        'class'=>'kartik\grid\EditableColumn',
        'attribute'=>'color',
        'edit_type' => FHtml::EDIT_TYPE_INLINE,

        'hAlign'=>'left',
        'vAlign'=>'middle',
        'format'=>'html',
        'value' => function($model) { return FHtml::showColor($model-> color); },
        'editableOptions' => [
            'header' => 'level',
            'size' => 'md',
            'type' => \kartik\color\ColorInput::className(),
            'options' => [
            ]
        ],
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
    ],
    [
        'class'=>'kartik\grid\DataColumn',
        'format'=>'html',
        'edit_type' => FHtml::EDIT_TYPE_INLINE,
        'attribute'=>'icon',
        'value' => function($model) { return FHtml::showImageThumbnail($model-> icon, FHtml::config('ADMIN_THUMBNAIL_WIDTH', 30), 'meta-setting'); },
        'hAlign'=>'center',
        'vAlign'=>'middle',
        //'width'=>'20px',
        'contentOptions'=>['class'=>'nowrap', 'style'=>'width:30px; max-width: 30px;'],
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
        'class'=>'kartik\grid\DataColumn',
        'format'=>'html',
        'edit_type' => FHtml::EDIT_TYPE_INLINE,
        'attribute' => 'is_active',
        //'value' => function($model) { return FHtml::showBoolean($model-> is_active); },
        'value' => function ($model) {
            return FHtml::showContent($model->is_active, FHtml::SHOW_ACTIVE, 'object_type', 'is_active', 'tinyint(1)', 'object-type');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '20px',
        'editableOptions'=> [
            'size'=>'md',
            'inputType'=>\kartik\editable\Editable::INPUT_SWITCH,
            'options'=>[
                'options'=>[
                    'pluginOptions'=>[
                        'autoclose' => true
                    ]
                ]
            ]
        ],
    ],
    //[
    //'class'=>'kartik\grid\DataColumn',
    //'attribute'=>'sort_order',
    //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //],
    //[
    //'class'=>'kartik\grid\EditableColumn',
    //'attribute'=>'application_id',
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-md-2 nowrap'],
    //],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'width'=>'80px',
        'urlCreator' => function($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => $model->id]);
        },
        'visibleButtons' =>[
            'view' => false,
            'update' => false,
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>FHtml::t('common', 'title.update'), 'data-toggle'=>'tooltip'],
        'deleteOptions'=>[
            'role'=>'modal-remote',
            'title'=>FHtml::t('common', 'title.delete'),
            'data-confirm'=>false,
            'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>FHtml::t('common', 'Confirmation'),
            'data-confirm-message'=>FHtml::t('common', 'messege.confirmdelete')
        ],
    ],
];