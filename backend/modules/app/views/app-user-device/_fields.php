<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;

use common\widgets\FCKEditor;
use kartik\money\MaskMoney;
use yii\widgets\MaskedInput;
use kartik\slider\Slider;

?>

<?php $form = \common\widgets\FActiveForm::begin([
    'id' => 'app-user-device-form',
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


<?= //name: user_id, comment: , dbType: int(11), phpType: integer, size: 11, allowNull: 1
//$form->field($model, 'user_id')->dropDownList(FHtml::getComboArray('app_user_device', 'app_user_device', 'user_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'user_id')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('app_user_device', 'app_user_device', 'user_id', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

<?= //name: ime, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:
$form->field($model, 'ime')->textInput() ?>

<?= //name: gcm_id, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:
//$form->field($model, 'gcm_id')->dropDownList(FHtml::getComboArray('app_user_device', 'app_user_device', 'gcm_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'gcm_id')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('app_user_device', 'app_user_device', 'gcm_id', true, 'id', 'name'), 'options' => ['multiple' => true], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

<?= //name: type, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull:
//$form->field($model, 'type')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'type')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]) ?>

<?= //name: status, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull:
//$form->field($model, 'status')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'status')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]) ?>


<?php \common\widgets\FActiveForm::end(); ?>



