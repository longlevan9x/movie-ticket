<?php
/**
* Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
* Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
* MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
* This is the customized model class for table "AppUser".
*/
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
use common\widgets\formfield\FormObjectFile;
use common\widgets\formfield\FormObjectAttributes;
use common\widgets\formfield\FormRelations;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'AppUser';
$moduleTitle = 'App User';
$moduleKey = 'app-user';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

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
'enableClientValidation' => false,
'enableAjaxValidation' => false,
'options' => [
    //'class' => 'form-horizontal',
    'enctype' => 'multipart/form-data'
]
]);
 ?>


<div class="form">
    <div class="row">
            <div class="profile-sidebar col-md-3">
                <div class="portlet light">
                                         <div class="margin-top-20">&nbsp;
                        <h4><b><?= $model->name  ?></b></h4>
                                                        <small class='text-default'><?= $model->description  ?></small>
                    </div>
                    <div class="margin-top-20">
                                            </div>
                    <div class="margin-top-20">
                        <?= FHtml::showLabel('app_user', 'app_user', 'gender', $model->gender) ?>
<?= FHtml::showLabel('app_user', 'app_user', 'type', $model->type) ?>
<?= FHtml::showLabel('app_user', 'app_user', 'status', $model->status) ?>
                    </div>

                    <!--
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li class="active">
                                <a href="#">
                                    <i class="icon-settings"></i> Edit Detail </a>
                            </li>
                        </ul>
                    </div>-->
                </div>
                <!-- END MENU -->
                <div class="portlet light">
                     <div class="row list-separated profile-stat">
                         <div class="col-md-4 col-sm-4 col-xs-6"><?= FHtml::showField('Rate Count', $model->rate_count, FHtml::SHOW_NUMBER) ?> </div></div>
                    <div>
                        <div class="row list-separated profile-stat">
                            <div class="col-md-6">
                                <?= FHtml::showField('Created', FHtml::getFieldValue($model, 'created_date'), FHtml::SHOW_DATE) ?>                                </div>
                            <div class="col-md-6">
                                                                </div>
                        </div>
                        <div class="row list-separated profile-stat">
                            <div class="col-md-6">
                                <?= FHtml::showField('Modified', $model->modified_date, FHtml::SHOW_DATE) ?>                                </div>
                            <div class="col-md-6">
                                                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', $moduleTitle) ?></span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"><?= FHtml::t('common', 'Info')?></a>
                            </li>
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"><?= FHtml::t('common', 'Uploads')?></a>
                            </li>
                            <li>
                                <a href="#tab_1_3" data-toggle="tab"><?= FHtml::t('common', 'Attributes')?></a>
                            </li>
                                                        </ul>
                    </div>
                    <div class="body">
                        <div class="form">
                            <div class="form-body">
                                <div class="tab-content">
                                    <div class="tab-pane active row" id="tab_1_1">
                                        <div class="col-md-12">
                                                   <?=  //name: name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'name')->textInput() ?>

       <?=  //name: username, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'username')->textInput() ?>

       <?=  //name: email, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:  
        $form->field($model, 'email')->input('email') ?>

       <?=  //name: description, comment: , dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1 
        $form->field($model, 'description')->textarea(['rows' => 3]) ?>

       <?=  //name: content, comment: , dbType: text, phpType: string, size: , allowNull: 1 
        $form->field($model, 'content')->widget(FCKEditor::className(), ['options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal'])  ?>

       <?=  //name: phone, comment: , dbType: varchar(25), phpType: string, size: 25, allowNull: 1 
        $form->field($model, 'phone')->textInput() ?>

       <?=  //name: rate, comment: group:RATINGS;, dbType: float, phpType: double, size: , allowNull: 1 
        $form->field($model, 'rate')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]) ?>

       <?=  //name: rate_count, comment: group:RATINGS;, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        $form->field($model, 'rate_count')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]])
 ?>

       <?=  //name: provider_id, comment: lookup:@provider, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'provider_id')->dropDownList(FHtml::getComboArray('@provider', 'provider', 'provider_id', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'provider_id')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('@provider', 'provider', 'provider_id', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

                                        </div>
                                    </div>

                                    <div class="tab-pane row" id="tab_1_2">
                                        <div class="col-md-12">
                                                   <?=  //name: avatar, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'avatar')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => [ 'model' => $model, 'maxFileSize'=> FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['accept' => 'image/*', 'multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true,'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])] ]]) ?>


                                            <?= FormObjectFile::widget( [
                                            'model' => $model, 'form' => $form,
                                            'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => 'object-file'
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="tab-pane row" id="tab_1_3">
                                        <div class="col-md-12">
                                            <?= FormObjectAttributes::widget( [
                                            'model' => $model, 'form' => $form,
                                            'canEdit' => $canEdit, 'moduleKey' => $moduleKey, 'modulePath' => $modulePath
                                            ]) ?>
                                        </div>
                                    </div>
                                    
                                    <!--<div class="tab-pane row" id="tab_1_p">
                                        <div class="col-md-12">
                                                                                    </div>
                                    </div>
                                    -->

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Grouping')?></span>
                        </div>
                    </div>
                    <div class="">
                        <div class="tab-content">
                            <div class="tab-pane active row" id="tab_1_1">
                                <div class="col-md-12">
                                           <?=  //name: type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'type')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'type', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'type')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'type', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: status, comment: data:PENDING,BANNED,REJECTED,NORMAL,PRO,VIP, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'status')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'status', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'status')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'status', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--
                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'Pricing')?></span>
                        </div>
                    </div>
                    <div class="">
                        <div class="tab-content">
                            <div class="tab-pane active row" id="tab_1_1">
                                <div class="col-md-12">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                -->
                <div class="portlet light">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'PERSONAL')?></span>
                            </div>
                        </div>
                        <div class="">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                               <?=  //name: gender, comment: group:PERSONAL, dbType: varchar(100), phpType: string, size: 100, allowNull: 1 
        //$form->field($model, 'gender')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'gender', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'gender')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'gender', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

       <?=  //name: dob, comment: group:PERSONAL, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'dob')->textInput() ?>

       <?=  //name: weight, comment: group:PERSONAL, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'weight')->textInput() ?>

       <?=  //name: height, comment: group:PERSONAL, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'height')->textInput() ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="portlet light">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'LOCATION')?></span>
                            </div>
                        </div>
                        <div class="">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
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

       <?=  //name: lat, comment: group:LOCATION, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'lat')->textInput() ?>

       <?=  //name: long, comment: group:LOCATION, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'long')->textInput() ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="portlet light">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'FINANCE')?></span>
                            </div>
                        </div>
                        <div class="">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                               <?=  //name: balance, comment: group:FINANCE, dbType: decimal(10,0), phpType: string, size: 10, allowNull: 1 
        $form->field($model, 'balance')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' =>  'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]) ?>

       <?=  //name: point, comment: group:FINANCE, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
        $form->field($model, 'point')->widget(kartik\slider\Slider::className(), ['pluginOptions' => ['placeholder' => 'Rate (0 - 10)...', 'html5Options' => ['min' => 0, 'max' => 100], 'addon' => ['append' => ['content' => 'star']]]]) ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="portlet light">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'PAYMENT')?></span>
                            </div>
                        </div>
                        <div class="">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                               <?=  //name: card_number, comment: group:PAYMENT, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'card_number')->textInput() ?>

       <?=  //name: card_cvv, comment: editor:text;group:PAYMENT, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'card_cvv')->textarea(['rows' => 3]) ?>

       <?=  //name: card_exp, comment: group:PAYMENT, dbType: varchar(255), phpType: string, size: 255, allowNull: 1 
        $form->field($model, 'card_exp')->textInput() ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="portlet light">
                        <div class="portlet-title tabbable-line">
                            <div class="caption caption-md">
                                <i class="icon-globe theme-font hide"></i>
                                <span class="caption-subject font-blue-madison bold uppercase"><?= FHtml::t('common', 'GROUPING')?></span>
                            </div>
                        </div>
                        <div class="">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                               <?=  //name: is_online, comment: group:GROUPING, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_online')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_online')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: is_active, comment: group:GROUPING, dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1 
        //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
$form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]]) ?>

       <?=  //name: role, comment: data:10:USER,20:MODERATOR,30:ADMIN;editor:select;group:GROUPING, dbType: int(2), phpType: integer, size: 2, allowNull: 1 
        //$form->field($model, 'role')->dropDownList(FHtml::getComboArray('app_user', 'app_user', 'role', true, 'id', 'name'), ['prompt' => ''])
$form->field($model, 'role')->widget(\kartik\widgets\Select2::className(),['data' => FHtml::getComboArray('app_user', 'app_user', 'role', true, 'id', 'name'), 'options'=>['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                

                <script language="javascript" type="text/javascript">
                    function submitForm($saveType) {
                        $('#saveType').val($saveType);
                    }
                </script>

                <?php if (Yii::$app->request->isAjax) { ?>

                    <input type="hidden" id="saveType" name="saveType">

                <?php } else { ?>
                <input type="hidden" id="saveType" name="saveType">

                <div class="">
                    <?php if ($canEdit) { echo                     FHtml::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('common', 'Save'), ['class' => 'btn btn-primary']);
                    echo '  ' . FHtml::submitButton('<i class="fa fa-copy"></i> ' . FHtml::t('common', 'Save') . ' & ' . FHtml::t('common', 'Clone'), ['class' => 'btn btn-warning', 'onclick' => 'submitForm("clone")']); } ?>
                    <?php  if (!$model->isNewRecord && $canDelete) {?>
                    <?=  FHtml::a('<i class="fa fa-trash"></i> ' . FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger pull-right',
                    'data' => [
                    'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
                    'method' => 'post',
                    ],
                    ]); ?>
                    <?php }  ?>
                    <?=  ' | ' . FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
                </div>
                <?php } ?>
            </div>
    </div>
</div>
   <?php \common\widgets\FActiveForm::end(); ?>




