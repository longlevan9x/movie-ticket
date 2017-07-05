<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use yii\widgets\MaskedInput;
use kartik\money\MaskMoney;
use kartik\slider\Slider;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'AppUser';
$moduleTitle = 'App User';
$moduleKey = 'app-user';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$field_layout = FHtml::config(FHtml::SETTINGS_FIELD_LAYOUT, FHtml::LAYOUT_TABLE);
$form_label_CSS = 'text-default';

/* @var $this yii\web\View */
/* @var $model backend\modules\app\models\AppUser */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
$this->title = FHtml::t($moduleTitle);
$this->params['mainIcon'] = 'fa fa-list';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
} ?>


<?php $form = \common\widgets\FActiveForm::begin([
'id' => 'app-user-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => false, // check the Role here
'readonly' => !$canEdit, // check the Role here
'options' => [
//'class' => 'form-horizontal',
'enctype' => 'multipart/form-data'
]
]);
 ?>


<div class="form">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="portlet light">
                <?= $this->render(\Globals::VIEWS_PRINT_HEADER, ['form_type' => $moduleName, 'title' => $model->name]) ?>                                <h3><?= FHtml::t('common', 'Common') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'id', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user', 'id', 'int(11)', '', '') ?>

       <?= FHtml::showModelField($model,'avatar', FHtml::SHOW_IMAGE, $field_layout, $form_label_CSS, 'app_user', 'avatar', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'name', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'name', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'username', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'username', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'email', FHtml::SHOW_EMAIL, $field_layout, $form_label_CSS, 'app_user', 'email', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'description', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'description', 'varchar(2000)', '', '') ?>

       <?= FHtml::showModelField($model,'content', FHtml::SHOW_HTML, $field_layout, $form_label_CSS, 'app_user', 'content', 'text', '', '') ?>

       <?= FHtml::showModelField($model,'phone', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'phone', 'varchar(25)', '', '') ?>

       <?= FHtml::showModelField($model,'country', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user', 'country', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'type', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user', 'type', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'status', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user', 'status', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'provider_id', FHtml::SHOW_LOOKUP, $field_layout, $form_label_CSS, '@provider', 'provider_id', 'varchar(100)', '', '') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                <h3><?= FHtml::t('common', 'PERSONAL') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'gender', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user', 'gender', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'dob', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'dob', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'weight', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'weight', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'height', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'height', 'varchar(255)', '', '') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                <h3><?= FHtml::t('common', 'LOCATION') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'address', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'address', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'state', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user', 'state', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'city', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user', 'city', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'lat', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'lat', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'long', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'long', 'varchar(255)', '', '') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                <h3><?= FHtml::t('common', 'FINANCE') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'balance', FHtml::SHOW_CURRENCY, $field_layout, $form_label_CSS, 'app_user', 'balance', 'decimal(10,0)', '', '') ?>

       <?= FHtml::showModelField($model,'point', $field_layout, $form_label_CSS, 'app_user', 'point', 'int(11)', '', '') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                <h3><?= FHtml::t('common', 'PAYMENT') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'card_number', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'card_number', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'card_cvv', FHtml::SHOW_HTML, $field_layout, $form_label_CSS, 'app_user', 'card_cvv', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'card_exp', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'app_user', 'card_exp', 'varchar(255)', '', '') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                <h3><?= FHtml::t('common', 'RATINGS') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'rate', FHtml::SHOW_DECIMAL, $field_layout, $form_label_CSS, 'app_user', 'rate', 'float', '', '') ?>

       <?= FHtml::showModelField($model,'rate_count', FHtml::SHOW_NUMBER, $field_layout, $form_label_CSS, 'app_user', 'rate_count', 'int(11)', '', '') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                                <h3><?= FHtml::t('common', 'GROUPING') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'is_online', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'app_user', 'is_online', 'tinyint(1)', '', '') ?>

       <?= FHtml::showModelField($model,'is_active', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'app_user', 'is_active', 'tinyint(1)', '', '') ?>

       <?= FHtml::showModelField($model,'role', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'app_user', 'role', 'int(2)', '', '') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                            </div>

        <?php if (Yii::$app->request->isAjax) { ?>

        <input type="hidden" id="saveType" name="saveType">

        <?php } else { ?>
        <p class="hidden-print">
            <a class="btn blue hidden-print " onclick="javascript:window.print();"> Print
                <i class="fa fa-print"></i>
            </a>
            <?php if (FHtml::isInRole('', 'update', $currentRole)) { Html::a('<i class="fa fa-pencil"></i> ' .  FHtml::t('common', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); } ?>
            <?php if (FHtml::isInRole('', 'delete', $currentRole)) {Html::a('<i class="fa fa-trash"></i> ' .  FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
            'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
            'method' => 'post',
            ],
            ]);} ?>
            <?=  Html::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'button.cancel'), ['index'], ['class' => 'btn btn-default']) ?>

        </p>
        <?php } ?>        </div>
    </div>
</div>
   <?php \common\widgets\FActiveForm::end(); ?>




