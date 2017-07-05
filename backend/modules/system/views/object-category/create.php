<?php
use yii\helpers\Html;
use common\components\FHtml;


$currentRole = FHtml::getCurrentRole();
$controlName = '';
$folder = ''; //manual edit files in 'live' folder only
$canCreate = true;

$moduleName = 'ObjectCategory';
$moduleTitle = 'Object Category';
$moduleKey = 'object-category';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => 'index'];
$this->params['breadcrumbs'][] = FHtml::t('common', 'Create');


if (FHtml::isInRole('', 'create', $currentRole)) {
    $controlName = $folder . '_form';
} else {
    $controlName = $folder . '_view';
    $canCreate = false;
}


/* @var $this yii\web\View */
/* @var $model backend\models\ObjectCategory */

?>
<div class="object-category-create">
    <?php if ($canCreate === true) {
        echo $this->render($controlName, [
            'model' => $model, 'modulePath' => $moduleKey, 'object_type' => FHtml::getRequestParam('object_type')
        ]);
    } else { ?>
        <?= Html::a(FHtml::t('common', 'button.cancel'), ['index'], ['class' => 'btn btn-default']);
    } ?>
</div>
