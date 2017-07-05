<?php
use common\widgets\BulkButtonWidget;
use yii\helpers\Html;
use common\components\FHtml;

use kartik\dropdown\DropdownX;

$moduleName = 'ObjectCategory';
$currentRole = FHtml::getCurrentRole();
$createButton = '';
if (FHtml::isInRole('', 'create', $currentRole)) {
    $createButton = FHtml::buttonCreate();
}

$deleteButton = ''; $deleteAllButton = '';
if (FHtml::isInRole('', 'delete', $currentRole)) {
    $deleteButton = FHtml::buttonDeleteBulk();
    $deleteAllButton = FHtml::buttonDeleteAll();

}

$bulkActionButton = '';
if (FHtml::isInRole('', 'action', $currentRole)) {
    $bulkActionButton = FHtml::buttonBulkActions(
        [
            FHtml::buildChangeFieldMenu('object_category', 'is_active'),
            FHtml::buildChangeFieldMenu('object_category', 'is_top'),
            FHtml::buildChangeFieldMenu('object_category', 'is_hot')
        ]
    );
}

return [
    [
        'content' =>
            $createButton . $deleteButton . $deleteAllButton .
            '{toggleData}' .
            $bulkActionButton,
        'options'=> ['class' => 'text-right kv-panel-before']
    ],
];