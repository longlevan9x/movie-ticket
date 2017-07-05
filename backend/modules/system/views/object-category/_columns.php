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
$moduleName = 'ObjectCategory';
$folder = 'live/';
$classControl = '';
$isEditable = true;
if (FHtml::isInRole('', 'update', $currentRole))
{
    $classControl = 'kartik\grid\EditableColumn';
    $isEditable = true;
}
else
{
    $classControl = 'kartik\grid\DataColumn';
    $isEditable = false;
}

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
//    [
//        'class'=>'kartik\grid\ExpandRowColumn',
//        'width'=>'50px',
//        'value'=>function ($model, $key, $index, $column) {
//        return GridView::ROW_COLLAPSED;
//        },
//        'detail'=>function ($model, $key, $index, $column) {
//        return Yii::$app->controller->renderPartial('_view', ['model'=>$model]);
//        },
//        'headerOptions'=>['class'=>'kartik-sheet-style'],
//        'expandOneOnly'=>false
//    ],
    //[ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:  
        //'class'=>$classControl,
        //'format'=>['decimal', 0],
        //'attribute'=>'id',
        //'value' => function($model) { return FHtml::showContent($model-> id, '', 'object_category', 'id','int(11)','object-category'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'width'=>'50px',
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_SPIN,
                            //'options'=>[
                                //'pluginOptions'=>[
                                    //'min'=>0, 'max' => 500000
                                //]
                            //]
                        //],
    //],
//    [ //name: object_type, dbType: varchar(50), phpType: string, size: 50, allowNull:
//        'class'=>$classControl,
//        'format'=>'raw',
//        'attribute'=>'object_type',
//        'value' => function($model) { return FHtml::showContent($model-> object_type, FHtml::SHOW_LABEL, 'object_category', 'object_type','varchar(50)','object-category'); },
//        'hAlign'=>'left',
//        'vAlign'=>'middle',
//        'filterType' => GridView::FILTER_SELECT2,
//        'filterWidgetOptions'=>[
//            'pluginOptions'=>['allowClear' => true],
//        ],
//        'filterInputOptions'=>['placeholder'=>''],
//        'group' => true,
//        'filter'=> FHtml::getComboArray('object_category.object_type', 'object_category', 'object_type', true, 'id', 'name'),
//        'contentOptions'=>['class'=>'col-md-1 nowrap'],
//        'editableOptions'=> function ($model, $key, $index, $widget) {
//            $fields = FHtml::getComboArray('object_category.object_type', 'object_category', 'object_type', true, 'id', 'name');
//            return [
//                'inputType' => 'dropDownList',
//                'displayValueConfig' => $fields,
//                'data' => $fields
//            ];
//        },
//    ],

    [ //name: image, dbType: varchar(255), phpType: string, size: 255, allowNull:
        'class'=>$classControl,
        'format'=>'html',
        'attribute'=>'image',
        'value' => function($model) { return FHtml::showImageThumbnail($model-> image, FHtml::config('ADMIN_THUMBNAIL_WIDTH', 30), 'object-category'); },
        'hAlign'=>'center',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],

    ],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class'=>$classControl,
        'attribute'=>'name',
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-5 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_TEXT,
                            'widgetClass'=> 'kartik\datecontrol\InputControl',
                            'options'=>[
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],
    //[ //name: description, dbType: text, phpType: string, size: , allowNull:  
        //'class'=>$classControl,
        //'attribute'=>'description',
        //'value' => function($model) { return FHtml::showContent($model-> description, '', 'object_category', 'description','text','object-category'); }, 
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
    //],
//    [ //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull:
//        'class'=>$classControl,
//        'format'=>['decimal', 0],
//        'attribute'=>'sort_order',
//        'value' => function($model) { return FHtml::showContent($model-> sort_order, '', 'object_category', 'sort_order','int(5)','object-category'); },
//        'hAlign'=>'right',
//        'vAlign'=>'middle',
//        'contentOptions'=>['class'=>'col-md-1 nowrap'],
//        'editableOptions'=>[
//                            'size'=>'md',
//                            'inputType'=>\kartik\editable\Editable::INPUT_SPIN,
//                            'options'=>[
//                                'pluginOptions'=>[
//                                    'min'=>0, 'max' => 500000
//                                ]
//                            ]
//                        ],
//    ],

    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
        'class' => $classControl,
        'format'=>'html',
        'attribute' => 'is_active',
        //'value' => function($model) { return FHtml::showBoolean($model-> is_active); },
        'value' => function ($model) {
            return FHtml::showContent($model->is_active, FHtml::SHOW_ACTIVE, 'object_type', 'is_active', 'tinyint(1)', 'object-type');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '20px',
        'editableOptions'=>[
            'size'=>'md',
            'inputType'=>\kartik\editable\Editable::INPUT_SWITCH,
            'options'=>[
                'options'=>[
                    'pluginOptions'=>[
                        'autoclose'=>true
                    ]
                ]
            ]
        ],
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
        'class' => $classControl,
        'format'=>'html',
        'attribute' => 'is_top',
        //'value' => function($model) { return FHtml::showBoolean($model-> is_active); },
        'value' => function ($model) {
            return FHtml::showContent($model->is_top, FHtml::SHOW_ACTIVE, 'object_type', 'is_top', 'tinyint(1)', 'object-type');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '20px',
        'editableOptions'=>[
            'size'=>'md',
            'inputType'=>\kartik\editable\Editable::INPUT_SWITCH,
            'options'=>[
                'options'=>[
                    'pluginOptions'=>[
                        'autoclose'=>true
                    ]
                ]
            ]
        ],
        'filterType' => null,

        'filter'=> null,
    ],
    [ //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
        'class' => $classControl,
        'format'=>'html',
        'attribute' => 'is_hot',
        //'value' => function($model) { return FHtml::showBoolean($model-> is_active); },
        'value' => function ($model) {
            return FHtml::showContent($model->is_hot, FHtml::SHOW_ACTIVE, 'object_type', 'is_hot', 'tinyint(1)', 'object-type');
        },
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '20px',
        'editableOptions'=>[
            'size'=>'md',
            'inputType'=>\kartik\editable\Editable::INPUT_SWITCH,
            'options'=>[
                'options'=>[
                    'pluginOptions'=>[
                        'autoclose'=>true
                    ]
                ]
            ]
        ],
    ],
    [ //name: parent_id, dbType: int(11), phpType: integer, size: 11, allowNull: 1
        'class'=> $classControl,
        //'format'=>['decimal', 0],
        'attribute'=>'parent_id',
        'contentOptions'=>['class'=>'col-md-3 nowrap'],
        'value' => function($model) { return FHtml::showContent($model-> parent_id, FHtml::SHOW_PARENT, 'object_category', 'parent_id','int(11)','object-category'); },
        'hAlign'=>'left',
        'vAlign'=>'middle',
        'width'=>'50px',
        'group' => true,
        'groupedRow' => false,
        'filterType' => null,

        'filter'=> null,
    ],
    //[ //name: created_date, dbType: datetime, phpType: string, size: , allowNull: 1 
        //'class'=>$classControl,
        //'format'=>'date',
        //'attribute'=>'created_date',
        //'hAlign'=>'right',
        //'vAlign'=>'middle',
        //'contentOptions'=>['class'=>'col-md-1 nowrap'],
        //'editableOptions'=>[                       
                            //'size'=>'md',
                            //'inputType'=>\kartik\editable\Editable::INPUT_WIDGET,
                            //'widgetClass'=> 'kartik\datecontrol\DateControl',
                            //'options'=>[
                                //'type'=>\kartik\datecontrol\DateControl::FORMAT_DATE,
                                //'displayFormat'=> FHtml::config('DATE_FORMAT', 'yyyy-MM-dd'),
                                //'saveFormat'=>'php:Y-m-d',
                                //'options'=>[
                                    //'pluginOptions'=>[
                                        //'autoclose'=>true
                                    //]
                                //]
                            //]
                        //],
    //],
    /*
    [ //name: modified_date, dbType: datetime, phpType: string, size: , allowNull: 1
        'class'=>$classControl,
        'format'=>'date',
        'attribute'=>'modified_date',
        'hAlign'=>'right',
        'vAlign'=>'middle',
        'contentOptions'=>['class'=>'col-md-1 nowrap'],
        'editableOptions'=>[                       
                            'size'=>'md',
                            'inputType'=>\kartik\editable\Editable::INPUT_WIDGET,
                            'widgetClass'=> 'kartik\datecontrol\DateControl',
                            'options'=>[
                                'type'=>\kartik\datecontrol\DateControl::FORMAT_DATE,
                                'saveFormat'=>'php:Y-m-d',
                                'options'=>[
                                    'pluginOptions'=>[
                                        'autoclose'=>true
                                    ]
                                ]
                            ]
                        ],
    ],*/
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
            'update' => FHtml::isInRole('', 'edit', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>FHtml::t('common', 'title.view'),'data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>$this->params['displayType'],'title'=>FHtml::t('common', 'title.update'), 'data-toggle'=>'tooltip'],
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