<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use kartik\widgets\Typeahead;
use common\components\FHtml;
use kartik\checkbox\CheckboxX;
use common\widgets\FCKEditor;
use kartik\money\MaskMoney;
use yii\widgets\MaskedInput;
use kartik\slider\Slider;


$form_Type = $this->params['activeForm_type'];
$moduleName = 'SettingsText';
$moduleTitle = 'Settings Text';
$moduleKey = 'settings-text';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\models\SettingsText */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if (!Yii::$app->request->isAjax) {
    $this->title = FHtml::t($moduleTitle);
    $this->params['mainIcon'] = 'fa fa-list';
    $this->params['toolBarActions'] = array(
        'linkButton' => array(),
        'button' => array(),
        'dropdown' => array(),
    );
} ?>
<?php if (Yii::$app->request->isAjax) { ?>

    <?php $form = \common\widgets\FActiveForm::begin(
        ['id' => 'settings-text-form',
            'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
            'staticOnly' => false, // check the Role here
            'readonly' => false, // check the Role here
            'options' => [//'class' => 'form-horizontal',
//'enctype' => 'multipart/form-data'
            ]]); ?>

    <input type="hidden" id="saveType" name="saveType">

    <?= //name: id, comment: , dbType: bigint(10), phpType: string, size: 10, allowNull:
    //$form->field($model, 'id')->dropDownList(FHtml::getComboArray('settings_text', 'settings_text', 'id', true, 'id', 'name'), ['prompt' => ''])
    $form->field($model, 'id')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('settings_text', 'settings_text', 'id', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

    <?= //name: name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:
    $form->field($model, 'name')->textInput() ?>

    <?= //name: description, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= //name: description_en, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_en')->textarea(['rows' => 3]) ?>

    <?= //name: description_es, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_es')->textarea(['rows' => 3]) ?>

    <?= //name: description_pt, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_pt')->textarea(['rows' => 3]) ?>

    <?= //name: description_de, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_de')->textarea(['rows' => 3]) ?>

    <?= //name: description_fr, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_fr')->textarea(['rows' => 3]) ?>

    <?= //name: description_it, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_it')->textarea(['rows' => 3]) ?>

    <?= //name: description_ko, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_ko')->textarea(['rows' => 3]) ?>

    <?= //name: description_ja, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_ja')->textarea(['rows' => 3]) ?>

    <?= //name: description_vi, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_vi')->textarea(['rows' => 3]) ?>

    <?= //name: description_zh, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'description_zh')->textarea(['rows' => 3]) ?>

    <?php \common\widgets\FActiveForm::end(); ?>


<?php } else { ?>

    <div class="settings-text-form">
        <div class="<?= $this->params['portletStyle'] ?>">
            <div class="portlet-title">
                <div class="caption font-dark">
                <span class="caption-subject bold uppercase">
                    <i class="<?php echo $this->params['mainIcon'] ?>"></i>
                    <?= FHtml::t('common', $moduleTitle) ?></span>
                    <span
                        class="caption-helper"><?= ($model->isNewRecord) ? FHtml::t('common', 'title.create') : FHtml::t('common', 'title.update') ?></span>
                </div>
                <div class="tools">
                    <a href="#" class="fullscreen"></a>
                    <a href="#" class="collapse"></a>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body form">
                <?php $form = \common\widgets\FActiveForm::begin([
                    'id' => 'settings-text-form',
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
                    <div class="form-body">

                        <?= //name: id, comment: , dbType: bigint(10), phpType: string, size: 10, allowNull:
                        //$form->field($model, 'id')->dropDownList(FHtml::getComboArray('settings_text', 'settings_text', 'id', true, 'id', 'name'), ['prompt' => ''])
                        $form->field($model, 'id')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('settings_text', 'settings_text', 'id', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

                        <?= //name: name, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:
                        $form->field($model, 'name')->textInput() ?>

                        <?= //name: description, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description')->textarea(['rows' => 3]) ?>

                        <?= //name: description_en, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_en')->textarea(['rows' => 3]) ?>

                        <?= //name: description_es, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_es')->textarea(['rows' => 3]) ?>

                        <?= //name: description_pt, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_pt')->textarea(['rows' => 3]) ?>

                        <?= //name: description_de, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_de')->textarea(['rows' => 3]) ?>

                        <?= //name: description_fr, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_fr')->textarea(['rows' => 3]) ?>

                        <?= //name: description_it, comment: group:EUROPE, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_it')->textarea(['rows' => 3]) ?>

                        <?= //name: description_ko, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_ko')->textarea(['rows' => 3]) ?>

                        <?= //name: description_ja, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_ja')->textarea(['rows' => 3]) ?>

                        <?= //name: description_vi, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_vi')->textarea(['rows' => 3]) ?>

                        <?= //name: description_zh, comment: group:ASIA, dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'description_zh')->textarea(['rows' => 3]) ?>

                    </div>
                    <div class="form-actions">
                        <?php if ($canEdit) {
                            echo Html::submitButton($model->isNewRecord ? FHtml::t('common', 'Create')
                                : FHtml::t('common', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' :
                                'btn btn-primary']);
                        } ?>
                        <?php if (!$model->isNewRecord && $canDelete) { ?>
                            <?= Html::a(FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
                                    'method' => 'post',
                                ],
                            ]); ?>
                        <?php } else { ?>
                            <?= Html::a(FHtml::t('common', 'button.cancel'), ['index'], ['class' => 'btn btn-default pull-right']) ?>
                        <?php } ?>                </div>
                </div>
                <?php \common\widgets\FActiveForm::end(); ?>
            </div>

        </div>
    </div>
<?php } ?>


