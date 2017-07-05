<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use \common\components\FHtml;
use common\components\Helper;
use common\widgets\FDetailView;
use yii\widgets\Pjax;

$moduleName = 'AppUser';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$currentAction = FHtml::currentAction();

$print = isset($print) ? $print : true;
$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppUser */
?>
<?php if (!Yii::$app->request->isAjax) {
$this->title = 'App Users';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
$this->params['mainIcon'] = 'fa fa-list';
} ?>
<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php if (Yii::$app->request->isAjax) { ?>
<div class="app-user-view">

       <?= FDetailView::widget([
    'model' => $model,
    'attributes' => [
                    'id',
                'avatar',
                'name',
                'username',
                'email',
                'password',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'description',
                'content',
                'gender',
                'dob',
                'phone',
                'weight',
                'height',
                'address',
                'country',
                'state',
                'city',
                'balance',
                'point',
                'card_number',
                'card_cvv',
                'card_exp',
                'lat',
                'long',
                'rate',
                'rate_count',
                'is_online',
                'is_active',
                'type',
                'status',
                'role',
                'provider_id',
                'created_date',
                'modified_date',
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
                'avatar',
                'name',
                'username',
                'email',
                'password',
                'auth_key',
                'password_hash',
                'password_reset_token',
                'description',
                'content',
                'gender',
                'dob',
                'phone',
                'weight',
                'height',
                'address',
                'country',
                'state',
                'city',
                'balance',
                'point',
                'card_number',
                'card_cvv',
                'card_exp',
                'lat',
                'long',
                'rate',
                'rate_count',
                'is_online',
                'is_active',
                'type',
                'status',
                'role',
                'provider_id',
                'created_date',
                'modified_date',
                ],
                ]) ?>

            </div>

        </div>

<?php } ?><?php if ($ajax) Pjax::end()  ?>

