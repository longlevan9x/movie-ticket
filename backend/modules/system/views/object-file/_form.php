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
$moduleName = 'ObjectFile';
$moduleTitle = 'Object File';
$moduleKey = 'object-file';

$currentRole = FHtml::getCurrentRole();
$canEdit = FHtml::isInRole('', FHtml::currentAction(), $currentRole);
$canDelete = FHtml::isInRole('', 'delete', $currentRole);

/* @var $this yii\web\View */
/* @var $model backend\models\ObjectFile */
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
        ['id' => 'object-file-form',
            'type' => $form_Type, //ActiveForm::TYPE_HORIZONTAL,ActiveForm::TYPE_VERTICAL,ActiveForm::TYPE_INLINE
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM, 'showErrors' => true],
            'staticOnly' => false, // check the Role here
            'readonly' => false, // check the Role here
            'options' => [//'class' => 'form-horizontal',
//'enctype' => 'multipart/form-data'
            ]]); ?>

    <input type="hidden" id="saveType" name="saveType">

    <?= //name: id, comment: , dbType: bigint(20), phpType: string, size: 20, allowNull:
    //$form->field($model, 'id')->dropDownList(FHtml::getComboArray('object_file', 'object_file', 'id', true, 'id', 'name'), ['prompt' => ''])
    $form->field($model, 'id')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('object_file', 'object_file', 'id', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

    <?= //name: object_id, comment: , dbType: int(11), phpType: integer, size: 11, allowNull:
    //$form->field($model, 'object_id')->dropDownList(FHtml::getComboArray('object_file', 'object_file', 'object_id', true, 'id', 'name'), ['prompt' => ''])
    $form->field($model, 'object_id')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('object_file', 'object_file', 'object_id', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

    <?= //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull:
    //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('object_file', 'object_file', 'object_type', true, 'id', 'name'), ['prompt' => ''])
    $form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('object_file', 'object_file', 'object_type', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

    <?= //name: file, comment: , dbType: varchar(555), phpType: string, size: 555, allowNull: 1
    $form->field($model, 'file')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['model' => $model, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]) ?>

    <?= //name: title, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:
    $form->field($model, 'title')->textInput() ?>

    <?= //name: description, comment: , dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1
    $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= //name: file_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1
    $form->field($model, 'file_type')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['model' => $model, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]) ?>

    <?= //name: file_size, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'file_size')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['model' => $model, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]) ?>

    <?= //name: file_duration, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1
    $form->field($model, 'file_duration')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['model' => $model, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]) ?>

    <?= //name: is_active, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
    //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
    $form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]) ?>

    <?= //name: sort_order, comment: , dbType: tinyint(5), phpType: integer, size: 5, allowNull: 1
    $form->field($model, 'sort_order')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' => 'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]])
    ?>

    <?php \common\widgets\FActiveForm::end(); ?>


<?php } else { ?>

    <div class="object-file-form">
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
                    'id' => 'object-file-form',
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

                        <?= //name: id, comment: , dbType: bigint(20), phpType: string, size: 20, allowNull:
                        //$form->field($model, 'id')->dropDownList(FHtml::getComboArray('object_file', 'object_file', 'id', true, 'id', 'name'), ['prompt' => ''])
                        $form->field($model, 'id')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('object_file', 'object_file', 'id', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

                        <?= //name: object_id, comment: , dbType: int(11), phpType: integer, size: 11, allowNull:
                        //$form->field($model, 'object_id')->dropDownList(FHtml::getComboArray('object_file', 'object_file', 'object_id', true, 'id', 'name'), ['prompt' => ''])
                        $form->field($model, 'object_id')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('object_file', 'object_file', 'object_id', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

                        <?= //name: object_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull:
                        //$form->field($model, 'object_type')->dropDownList(FHtml::getComboArray('object_file', 'object_file', 'object_type', true, 'id', 'name'), ['prompt' => ''])
                        $form->field($model, 'object_type')->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray('object_file', 'object_file', 'object_type', true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]) ?>

                        <?= //name: file, comment: , dbType: varchar(555), phpType: string, size: 555, allowNull: 1
                        $form->field($model, 'file')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['model' => $model, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]) ?>

                        <?= //name: title, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull:
                        $form->field($model, 'title')->textInput() ?>

                        <?= //name: description, comment: , dbType: varchar(2000), phpType: string, size: 2000, allowNull: 1
                        $form->field($model, 'description')->textarea(['rows' => 3]) ?>

                        <?= //name: file_type, comment: , dbType: varchar(100), phpType: string, size: 100, allowNull: 1
                        $form->field($model, 'file_type')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['model' => $model, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]) ?>

                        <?= //name: file_size, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'file_size')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['model' => $model, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]) ?>

                        <?= //name: file_duration, comment: , dbType: varchar(255), phpType: string, size: 255, allowNull: 1
                        $form->field($model, 'file_duration')->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['model' => $model, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['multiple' => true], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]) ?>

                        <?= //name: is_active, comment: , dbType: tinyint(1), phpType: integer, size: 1, allowNull: 1
                        //$form->field($model, 'is_active')->widget(\kartik\widgets\SwitchInput::className(), [])
                        $form->field($model, 'is_active')->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]) ?>

                        <?= //name: sort_order, comment: , dbType: tinyint(5), phpType: integer, size: 5, allowNull: 1
                        $form->field($model, 'sort_order')->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' => 'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]])
                        ?>

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


