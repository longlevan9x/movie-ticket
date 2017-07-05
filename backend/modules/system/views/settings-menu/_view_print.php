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

$moduleName = 'SettingsMenu';
$moduleTitle = 'Cms Menu';
$moduleKey = 'settingsmenu';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$field_layout = FHtml::config(FHtml::SETTINGS_FIELD_LAYOUT, FHtml::LAYOUT_TABLE);
$form_label_CSS = 'text-default';

/* @var $this yii\web\View */
/* @var $model backend\models\SettingsMenu */
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
'id' => 'settingsmenu-form',
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
                 <h3><?= FHtml::t('common', 'Common') ?></h3>
                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '<table class="table table-bordered">' : '' ?>                       <?= FHtml::showModelField($model,'id', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_menu', 'id', 'int(11)', '', '') ?>

       <?= FHtml::showModelField($model,'icon', FHtml::SHOW_IMAGE, $field_layout, $form_label_CSS, 'settings_menu', 'icon', 'varchar(300)', '', '') ?>

       <?= FHtml::showModelField($model,'name', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_menu', 'name', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'url', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_menu', 'url', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'module', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_menu', 'module', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'group', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_menu', 'group', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'role', FHtml::SHOW_TEXT, $field_layout, $form_label_CSS, 'settings_menu', 'role', 'varchar(255)', '', '') ?>

       <?= FHtml::showModelField($model,'object_type', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_menu', 'object_type', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'menu_type', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_menu', 'menu_type', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'display_type', FHtml::SHOW_LABEL, $field_layout, $form_label_CSS, 'settings_menu', 'display_type', 'varchar(100)', '', '') ?>

       <?= FHtml::showModelField($model,'is_active', FHtml::SHOW_ACTIVE, $field_layout, $form_label_CSS, 'settings_menu', 'is_active', 'tinyint(4)', '', '') ?>

                <?= ($field_layout == FHtml::LAYOUT_TABLE) ? '</table>' : '' ?>                            </div>

        <?php if (Yii::$app->request->isAjax) { ?>

        <input type="hidden" id="saveType" name="saveType">

        <?php } else { ?>
        <p class="hidden-print">
            <a class="btn blue hidden-print " onclick="javascript:window.print();"> Print
                <i class="fa fa-print"></i>
            </a>
            <?php if (FHtml::isInRole('', 'update', $currentRole)) { FHtml::a('<i class="fa fa-pencil"></i> ' .  FHtml::t('common', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); } ?>
            <?php if (FHtml::isInRole('', 'delete', $currentRole)) {FHtml::a('<i class="fa fa-trash"></i> ' .  FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
            'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
            'method' => 'post',
            ],
            ]);} ?>
            <?=  FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'button.cancel'), ['index'], ['class' => 'btn btn-default']) ?>

        </p>
        <?php } ?>        </div>
    </div>
</div>
   <?php \common\widgets\FActiveForm::end(); ?>




