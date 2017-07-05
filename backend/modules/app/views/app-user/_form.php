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
use common\widgets\FActiveForm;
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
use common\widgets\FFormTable;
use yii\widgets\Pjax;

$form_Type = $this->params['activeForm_type'];

$moduleName = 'AppUser';
$moduleTitle = 'App User';
$moduleKey = 'app-user';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);
$currentAction = FHtml::currentAction();
$edit_type = isset($edit_type) ? $edit_type : (FHtml::isViewAction($currentAction) ? FHtml::EDIT_TYPE_VIEW : FHtml::EDIT_TYPE_DEFAULT);
$display_type = isset($display_type) ? $display_type : (FHtml::isViewAction($currentAction) ? FHtml::DISPLAY_TYPE_TABLE : FHtml::DISPLAY_TYPE_DEFAULT);

$ajax = isset($ajax) ? $ajax : (FHtml::isListAction($currentAction) ? false : true);

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

<?php if ($ajax) Pjax::begin(['id' => 'crud-datatable'])  ?>

<?php $form = FActiveForm::begin([
'id' => 'app-user-form',
'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
'staticOnly' => false, // check the Role here
'readonly' => !$canEdit, // check the Role here
'edit_type' => $edit_type,
'display_type' => $display_type,
'enableClientValidation' => true,
'enableAjaxValidation' => false,
'options' => [
    //'class' => 'form-horizontal',
    'enctype' => 'multipart/form-data'
]
]);
 ?>


<div class="form">
    <div class="row">

        <div class="col-md-9">
            <div class="portlet light">
                <div class="visible-print">
                    <?= (FHtml::isViewAction($currentAction)) ?  FHtml::showPrintHeader($moduleName) : ''  ?>
                </div>
                <div class="portlet-title tabbable-line hidden-print">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">
                            <?= FHtml::t('common', $moduleTitle) ?> : <?= FHtml::showObjectConfigLink($model) ?>                        </span>
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
                                                </ul>
                </div>
                <div class="portlet-body form">
                    <div class="form">
                        <div class="form-body">
                            <div class="tab-content">
                                <div class="tab-pane active row" id="tab_1_1">
                                    <div class="col-md-12">
                                               <?php echo FFormTable::widget(['model' => $model, 'form' => $form, 'columns' => 1, 'attributes' => [ 
                                        'name' => ['value' => $form->fieldNoLabel($model, 'name')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'username' => ['value' => $form->fieldNoLabel($model, 'username')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'email' => ['value' => $form->fieldNoLabel($model, 'email')->emailInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'description' => ['value' => $form->fieldNoLabel($model, 'description')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'content' => ['value' => $form->fieldNoLabel($model, 'content')->html() , 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'phone' => ['value' => $form->fieldNoLabel($model, 'phone')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'rate' => ['value' => $form->fieldNoLabel($model, 'rate')->money(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'rate_count' => ['value' => $form->fieldNoLabel($model, 'rate_count')->numeric(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'provider_id' => ['value' => $form->fieldNoLabel($model, 'provider_id')->select(FHtml::getComboArray('@provider', 'provider', 'provider_id', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],


                                              ]]); ?>


                                        <!--
                                        
                                        -->


                                               <?php echo FFormTable::widget(['model' => $model, 'title' => 'Group', 'form' => $form, 'columns' => 2, 'attributes' => [ 

                                            'type' => ['value' => $form->fieldNoLabel($model, 'type')->select(FHtml::getComboArray('app_user', 'app_user', 'type', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'status' => ['value' => $form->fieldNoLabel($model, 'status')->select(FHtml::getComboArray('app_user', 'app_user', 'status', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],

                                                
                                              ]]); ?>



                                                                                           <?php echo FFormTable::widget(['model' => $model, 'title' => 'PERSONAL', 'form' => $form, 'columns' => 1, 'attributes' => [ 

                                            'gender' => ['value' => $form->fieldNoLabel($model, 'gender')->select(FHtml::getComboArray('app_user', 'app_user', 'gender', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'dob' => ['value' => $form->fieldNoLabel($model, 'dob')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'weight' => ['value' => $form->fieldNoLabel($model, 'weight')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'height' => ['value' => $form->fieldNoLabel($model, 'height')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],


                                                  ]]); ?>

                                                   <?php echo FFormTable::widget(['model' => $model, 'title' => 'LOCATION', 'form' => $form, 'columns' => 1, 'attributes' => [ 

                                            'address' => ['value' => $form->fieldNoLabel($model, 'address')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'country' => ['value' => $form->fieldNoLabel($model, 'country')->select(FHtml::getComboArray('app_user', 'app_user', 'country', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'state' => ['value' => $form->fieldNoLabel($model, 'state')->select(FHtml::getComboArray('app_user', 'app_user', 'state', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'city' => ['value' => $form->fieldNoLabel($model, 'city')->select(FHtml::getComboArray('app_user', 'app_user', 'city', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'lat' => ['value' => $form->fieldNoLabel($model, 'lat')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'long' => ['value' => $form->fieldNoLabel($model, 'long')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],


                                                  ]]); ?>

                                                   <?php echo FFormTable::widget(['model' => $model, 'title' => 'FINANCE', 'form' => $form, 'columns' => 1, 'attributes' => [ 

                                            'balance' => ['value' => $form->fieldNoLabel($model, 'balance')->currency(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'point' => ['value' => $form->fieldNoLabel($model, 'point')->range(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],


                                                  ]]); ?>

                                                   <?php echo FFormTable::widget(['model' => $model, 'title' => 'PAYMENT', 'form' => $form, 'columns' => 1, 'attributes' => [ 

                                            'card_number' => ['value' => $form->fieldNoLabel($model, 'card_number')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'card_cvv' => ['value' => $form->fieldNoLabel($model, 'card_cvv')->textarea(['rows' => 3]), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'card_exp' => ['value' => $form->fieldNoLabel($model, 'card_exp')->textInput(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],


                                                  ]]); ?>

                                                   <?php echo FFormTable::widget(['model' => $model, 'title' => 'GROUPING', 'form' => $form, 'columns' => 1, 'attributes' => [ 

                                            'is_online' => ['value' => $form->fieldNoLabel($model, 'is_online')->checkbox() , 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'is_active' => ['value' => $form->fieldNoLabel($model, 'is_active')->checkbox() , 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],
'role' => ['value' => $form->fieldNoLabel($model, 'role')->select(FHtml::getComboArray('app_user', 'app_user', 'role', true, 'id', 'name')), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],


                                                  ]]); ?>


                                    </div>
                                </div>

                                <div class="tab-pane row" id="tab_1_2">
                                    <div class="col-md-12">
                                               <?php echo FFormTable::widget(['model' => $model, 'title' => '', 'form' => $form, 'columns' => 1, 'attributes' => [ 

                                        'avatar' => ['value' => $form->fieldNoLabel($model, 'avatar')->image(), 'columnOptions' => ['colspan' => 1], 'type' => FHtml::INPUT_RAW],

                                              ]]); ?>



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

            
            <?php            $type = FHtml::getFieldValue($model, 'type');
            if (isset($modelMeta) && !empty($type))
                echo FHtml::render('..\\' . $moduleKey . '-' . $type . '\\_form.php', '', ['model' => $modelMeta, 'display_actions' => false, 'canEdit' => $canEdit, 'canDelete' => $canDelete]);
              ?>
            <?= (FHtml::isViewAction($currentAction)) ?  FHtml::showViewButtons($model, $canEdit, $canDelete) : FHtml::showActionsButton($model, $canEdit, $canDelete)  ?>

        </div>
        <div class="profile-sidebar col-md-3 col-xs-12 hidden-print">
            <div class="portlet light">
                <?= FHtml::showModelPreview($model) ?>
            </div>
        </div>
    </div>
</div>
   <?php FActiveForm::end(); ?>
<?php if ($ajax) Pjax::end()  ?>
