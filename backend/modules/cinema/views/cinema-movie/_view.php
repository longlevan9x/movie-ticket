<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'CinemaMovie';

$currentRole = FHtml::getCurrentRole();
$currentAction = FHtml::currentAction();

$canEdit = FHtml::isInRole('', $currentAction, $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\cinema\models\CinemaMovie */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'Cinema Movies';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="cinema-movie-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'image',
                'code',
                'name',
                'description',
                'content',
                'director',
                'writer',
                'runtime',
                'trailer',
                'technology',
                'mpaa',
                'country',
                'rates',
                'count_rates',
                'release_date',
                'close_date',
                'status',
                'sort_order',
                'type',
                'is_active',
                'created_date',
                'modified_date',
                'application_id',
    ],
    ]) ?>
</div>
<?php } else { ?>

        <div class="row" style="padding: 20px">
            <div class="col-md-12" style="background-color: white; padding: 20px">
                <?= FDetailView::widget([
                'model' => $model,
                'attributes' => [
                                'id',
                'image',
                'code',
                'name',
                'description',
                'content',
                'director',
                'writer',
                'runtime',
                'trailer',
                'technology',
                'mpaa',
                'country',
                'rates',
                'count_rates',
                'release_date',
                'close_date',
                'status',
                'sort_order',
                'type',
                'is_active',
                'created_date',
                'modified_date',
                'application_id',
                ],
                ]) ?>

            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

