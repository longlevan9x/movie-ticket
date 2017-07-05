<?php

use yii\helpers\Html;
use common\components\FHtml;


$moduleName = 'ObjectCategory';
$moduleTitle = 'Object Category';
$moduleKey = 'object-category';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Update');
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$currentRole = FHtml::getCurrentRole();

if (FHtml::isInRole('', 'update', $currentRole)) {
    $controlName = $folder . '_form';
} else {
    $controlName = $folder . '_view';
}

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectCategory */
?>
<div class="object-category-update">
    <?= $this->render($controlName, [
        'model' => $model, 'modulePath' => $moduleKey, 'object_type' => FHtml::getRequestParam('object_type')

    ]) ?>
</div>
