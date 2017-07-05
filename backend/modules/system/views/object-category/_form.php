<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;


$form_Type = $this->params['activeForm_type'];
$moduleName = 'ObjectCategory';
$moduleTitle = FHtml::t('common', 'Object Category');
$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$object_type = isset($model) ? $model->object_type : FHtml::getRequestParam('object_type');

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
$this->title = 'Object Categories';
$this->params['mainIcon'] = 'fa fa-list';
$this->params['toolBarActions'] = array(
'linkButton'=>array(),
'button'=>array(),
'dropdown'=>array(),
);
} ?>
<?php if (Yii::$app->request->isAjax) { ?>

<?php $form = \common\widgets\FActiveForm::begin(
['id' => 'object-category-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => false, // check the Role here
'readonly' => false, // check the Role here
'options' => [//'class' => 'form-horizontal',
//'enctype' => 'multipart/form-data'
]]); ?>

<input type="hidden" id="saveType" name="saveType">

    <?=  //name: parent_id, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
$form->field($model, 'parent_id')->dropDownList(FHtml::getComboArray('object_category.parent_id', 'object_category', 'parent_id', true, 'id', 'name'), ['prompt' => '']) ?>

    <?=  //name: image, dbType: varchar(255), phpType: string, size: 255, allowNull:  
$form->field($model, 'image')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => [ 'model' => $model, 'maxFileSize'=> FHtml::config('FILE_SIZE_MAX', 4000), 'options' => ['accept' => 'image/*', 'multiple' => false],'pluginOptions' => ['previewFileType' => 'image', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])], 'showPreview' => true, 'showCaption' => true, 'showRemove' => true,'showUpload' => false ]]) ?>

    <?=  //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
$form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?=  //name: description, dbType: text, phpType: string, size: , allowNull:  
$form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?=  //name: sort_order, dbType: int(5), phpType: integer, size: 5, allowNull:  
$form->field($model, 'sort_order')->widget(\kartik\widgets\TouchSpin::className(), ['pluginOptions' => [ 'initval' => 1, 'min' => 0, 'max' => 10000000, 'step' => 1, 'decimals' => 0,  'verticalbuttons' => true, 'verticalupclass' => 'glyphicon glyphicon-plus', 'verticaldownclass' => 'glyphicon glyphicon-minus','prefix' => '', 'postfix' => '']]) ?>

    <?=  //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:  
$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), []) ?>

    <?=  //name: object_type, dbType: varchar(50), phpType: string, size: 50, allowNull:  
$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('object_category.object_type', 'object_category', 'object_type', true, 'id', 'name'), ['prompt' => '']) ?>

    <?=  //name: created_date, dbType: datetime, phpType: string, size: , allowNull: 1 
$form->field($model, 'created_date')->widget(\kartik\widgets\DateTimePicker::className(), ['convertFormat' => true, 'pluginOptions' => ['autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true, 'daysOfWeekDisabled' => FHtml::config('DAYS_OF_WEEK_DISABLED', '0,6'), 'hoursDisabled' => FHtml::config('HOURS_DISABLED', '0,1,2,3,4,5,6,7,8,19,20,21,22'), 'format' => FHtml::config('FORMAT_TIMESTAMP', 'D, dd-M-yyyy, HH:ii P')]]) ?>

    <?=  //name: modified_date, dbType: datetime, phpType: string, size: , allowNull: 1
$form->field($model, 'modified_date')->widget(\kartik\widgets\DateTimePicker::className(), ['convertFormat' => true, 'pluginOptions' => ['autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true, 'daysOfWeekDisabled' => FHtml::config('DAYS_OF_WEEK_DISABLED', '0,6'), 'hoursDisabled' => FHtml::config('HOURS_DISABLED', '0,1,2,3,4,5,6,7,8,19,20,21,22'), 'format' => FHtml::config('FORMAT_TIMESTAMP', 'D, dd-M-yyyy, HH:ii P')]]) ?>

   <?php \common\widgets\FActiveForm::end(); ?>


<?php } else { ?>

            <?php $form = \common\widgets\FActiveForm::begin([
            'id' => 'object-category-form',
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
                    <div class="profile-sidebar col-md-3">
                        <div class="portlet light profile-sidebar-portlet ">
                            <div class="profile-userpic">
                                <?= FHtml::showImage($model->image, 'object-category', '100%', 0)  ?>
                            </div>
                            <div class="profile-usertitle">
                                <div class="profile-usertitle-name"> <br/><h4><b><?= $model->name ?> </b></h4></div>
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
                            <!--  <div class="row list-separated profile-stat">
                                  <div class="col-md-4 col-sm-4 col-xs-6">
                                      <div class="uppercase profile-stat-title"> 37 </div>
                                      <div class="uppercase profile-stat-text"> Projects </div>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-6">
                                      <div class="uppercase profile-stat-title"> 51 </div>
                                      <div class="uppercase profile-stat-text"> Tasks </div>
                                  </div>
                                  <div class="col-md-4 col-sm-4 col-xs-6">
                                      <div class="uppercase profile-stat-title"> 61 </div>
                                      <div class="uppercase profile-stat-text"> Uploads </div>
                                  </div>
                              </div>-->
                            <div>
                                <div class="margin-top-20 profile-desc-link">
                                    <?= FHtml::showField('Created', $model->created_date, FHtml::SHOW_DATE) ?>
                                </div>
                                <div class="margin-top-20 profile-desc-link">
                                    <?= FHtml::showField('Updated', $model->modified_date, FHtml::SHOW_DATE) ?>
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
                                            <a href="#tab_1_1" data-toggle="tab">Info</a>
                                        </li>
                                        <li>
                                            <a href="#tab_1_2" data-toggle="tab">Images</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="body">
                                    <div class="form">
                                        <div class="form-body">
                                            <div class="tab-content">
                                                <div class="tab-pane active row" id="tab_1_1">
                                                    <div class="col-md-12">
                                                               <?=  //name: parent_id, dbType: int(11), phpType: integer, size: 11, allowNull: 1 
$form->field($model, 'parent_id')->dropDownList(FHtml::getComboArray('object_category.parent_id', 'object_category', 'parent_id', true, 'id', 'name'), ['prompt' => '']) ?>


       <?=  //name: name, dbType: varchar(255), phpType: string, size: 255, allowNull:  
$form->field($model, 'name')->textInput(['maxlength' => true]) ?>

       <?=  //name: description, dbType: text, phpType: string, size: , allowNull:  
$form->field($model, 'description')->textarea(['rows' => 3]) ?>
       <?=  //name: is_active, dbType: tinyint(1), phpType: integer, size: 1, allowNull:
$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), []) ?>

                                                    </div>
                                                </div>
                                                <div class="tab-pane row" id="tab_1_2">
                                                    <div class="col-md-12">
                                                        <?=  //name: image, dbType: varchar(255), phpType: string, size: 255, allowNull:
                                                        $form->field($model, 'image')->image() ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <?php if ($canEdit) { echo Html::submitButton($model->isNewRecord ? FHtml::t('common', 'Create')
                                : FHtml::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' :
                                'btn btn-primary']);} ?>
                                <?php  if (!$model->isNewRecord && $canDelete) {?>
                                <?=  Html::a(FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
                                'method' => 'post',
                                ],
                                ]); ?>
                                <?=  Html::a(FHtml::t('common', 'button.cancel'), ['index', 'object_type' => $object_type], ['class' => 'btn btn-default']) ?>
                                <?php } else { ?>
                                <?=  Html::a(FHtml::t('common', 'button.cancel'), ['index', 'object_type' => $object_type], ['class' => 'btn btn-default']) ?>
                                <?php } ?>                            </div>
                        </div>
                </div>
            </div>
               <?php \common\widgets\FActiveForm::end(); ?>

<?php } ?>


