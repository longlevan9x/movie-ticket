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


       <?=  //name: avatar, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'avatar')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => [ 'model' => $model, 'maxFileSize'=> FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['accept' => 'image/*', 'multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true,'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])] ]]) ?>

       <?=  //name: name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'name')->textInput() ?>

       <?=  //name: username, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'username')->textInput() ?>

       <?=  //name: email, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'email')->input('email') ?>

       <?=  //name: password, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'password')->passwordInput() ?>

       <?=  //name: auth_key, comment: , dbType: varchar(32), phpType: string, size: 32, allowNull: 1 
        $form->field($model, 'auth_key')->textInput() ?>

       <?=  //name: password_hash, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'password_hash')->textInput() ?>

       <?=  //name: password_reset_token, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'password_reset_token')->textInput() ?>

       <?=  //name: description, comment: , dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        $form->field($model, 'description')->textarea(['rows' => 3]) ?>

       <?=  //name: content, comment: , dbType: text, phpType: string, size: , allowNull: 1 
        $form->field($model, 'content')->widget(FCKEditor::className(), ['options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal'])  ?>

       <?=  //name: gender, comment: group:PERSONAL, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'gender')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'gender', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'gender')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'gender', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: dob, comment: group:PERSONAL, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'dob')->textInput() ?>

       <?=  //name: phone, comment: , dbType: varchar(25), phpType: string, size: 25, allowNull: 1 
        $form->field($model, 'phone')->textInput() ?>

       <?=  //name: weight, comment: group:PERSONAL, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'weight')->textInput() ?>

       <?=  //name: height, comment: group:PERSONAL, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'height')->textInput() ?>

       <?=  //name: address, comment: group:LOCATION, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'address')->textInput() ?>

       <?=  //name: country, comment: group:LOCATION, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'country')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'country', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'country')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'country', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: state, comment: group:LOCATION, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'state')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'state', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'state')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'state', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: city, comment: group:LOCATION, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'city')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'city', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'city')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'city', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: balance, comment: group:FINANCE, dbType: decimal(10,0), phpType: string, size: 10, allowNull: 1 
        $form->field($model, 'balance')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]) ?>

       <?=  //name: point, comment: group:FINANCE, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        $form->field($model, 'point')->widget(kartik\slider\Slider::className(), ['pluginOptions' => ['placeholder' => 'Rate (0 - 10)...', 'html5Options' => ['min' => 0, 'max' => 100], 'addon' => ['append' => ['content' => 'star']]]]) ?>

       <?=  //name: card_number, comment: group:PAYMENT, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'card_number')->textInput() ?>

       <?=  //name: card_cvv, comment: editor:text;group:PAYMENT, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'card_cvv')->textarea(['rows' => 3]) ?>

       <?=  //name: card_exp, comment: group:PAYMENT, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'card_exp')->textInput() ?>

       <?=  //name: lat, comment: group:LOCATION, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'lat')->textInput() ?>

       <?=  //name: long, comment: group:LOCATION, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'long')->textInput() ?>

       <?=  //name: rate, comment: group:RATINGS;, dbType: float, phpType: double, size: , allowNull: 1 
        $form->field($model, 'rate')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]) ?>

       <?=  //name: rate_count, comment: group:RATINGS;, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        $form->field($model, 'rate_count')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]])
 ?>

       <?=  //name: is_online, comment: group:GROUPING, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_online')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_online')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: is_active, comment: group:GROUPING, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'type')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: status, comment: data:PENDING,BANNED,REJECTED,NORMAL,PRO,VIP, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'status')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'status', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'status')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'status', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: role, comment: data:10:USER,20:MODERATOR,30:ADMIN;editor:select;group:GROUPING, dbType: int(2), phpType: integer, size: 2, allowNull: 1 
        //$form->field($model, 'role')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'role', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'role')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'role', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: provider_id, comment: lookup:@provider, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'provider_id')->dropDownList(FHtml::getComboArray('@provider', 'provider', 'provider_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'provider_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('@provider', 'provider', 'provider_id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>


   <?php \common\widgets\FActiveForm::end(); ?>



