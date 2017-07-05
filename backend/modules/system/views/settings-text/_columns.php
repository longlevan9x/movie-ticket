<?php
use yii\helpers\Url;
use common\components\FHtml;

use kartik\datecontrol\DateControl;
use kartik\grid\GridView;

$moduleName = 'SettingsText';
$currentRole = FHtml::getCurrentRole();

return [
    [
        'class' => 'common\widgets\grid\CheckboxColumn',
        'width' => '20px',
        'headerOptions' => ['class' => 'hidden-print'],
        'contentOptions' => ['class' => 'hidden-print'],
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
            return Yii::$app->controller->renderPartial('_view_print', ['model' => $model]);
        },
        'headerOptions' => ['class' => 'hidden-print'],
        'contentOptions' => ['class' => 'hidden-print'],
        'expandOneOnly' => false
    ],
    //[ //name: id, dbType: bigint(10), phpType: string, size: 10, allowNull:  
    //'class'=>'kartik\grid\DataColumn',
    //'format'=>['raw'],
    //'attribute'=>'id',
    //'value' => function($model) { return FHtml::showContent($model-> id, '', 'settings_text', 'id','bigint(10)','settings-text'); },
    //'hAlign'=>'right',
    //'vAlign'=>'middle',
    //'width'=>'50px',
    //],
    [ //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'name',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-xs-2 nowrap'],
    ],
    //[ //name: description, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
    //'class'=>'kartik\grid\DataColumn',
    //'attribute'=>'description',
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    //],
    [ //name: description_en, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'description_en',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-xs-2 nowrap'],
    ],
    [ //name: description_es, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'description_es',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-xs-2 nowrap'],
    ],
    [ //name: description_pt, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'description_pt',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-xs-2 nowrap'],
    ],
    [ //name: description_de, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'description_de',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-xs-2 nowrap'],
    ],
    [ //name: description_fr, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'description_fr',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-xs-2 nowrap'],
    ],
    [ //name: description_it, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'description_it',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'contentOptions' => ['class' => 'col-xs-2 nowrap'],
    ],
    //[ //name: description_ko, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
    //'class'=>'kartik\grid\DataColumn',
    //'attribute'=>'description_ko',
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    //],
    //[ //name: description_ja, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
    //'class'=>'kartik\grid\DataColumn',
    //'attribute'=>'description_ja',
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    //],
    //[ //name: description_vi, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
    //'class'=>'kartik\grid\DataColumn',
    //'attribute'=>'description_vi',
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    //],
    //[ //name: description_zh, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
    //'class'=>'kartik\grid\DataColumn',
    //'attribute'=>'description_zh',
    //'hAlign'=>'left',
    //'vAlign'=>'middle',
    //'contentOptions'=>['class'=>'col-xs-2 nowrap'],
    //],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => $this->params['buttonsType'], // Dropdown or Buttons
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '80px',
        'urlCreator' => function ($action, $model) {
            return FHtml::createBackendActionUrl([$action, 'id' => $model->id]);
        },
        'headerOptions' => ['class' => 'hidden-print'],
        'contentOptions' => ['class' => 'hidden-print'],
        'visibleButtons' => [
            'update' => FHtml::isInRole('', 'update', $currentRole),
            'delete' => FHtml::isInRole('', 'delete', $currentRole),
        ],
        'viewOptions' => ['role' => 'modal-remote', 'title' => FHtml::t('common', 'title.view'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => $this->params['displayType'], 'title' => FHtml::t('common', 'title.update'), 'data-toggle' => 'tooltip'],
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