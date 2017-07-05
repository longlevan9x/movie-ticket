<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\ObjectCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'ObjectCategory';
$moduleTitle = 'Object Category';
$moduleKey = 'object-category';

$this->title = FHtml::t($moduleTitle);

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = $this->title;

$this->params['toolBarActions'] = array(
    'linkButton' => array(),
    'button' => array(),
    'dropdown' => array(),
);
$this->params['mainIcon'] = 'fa fa-list';

CrudAsset::register($this);

$currentRole = FHtml::getCurrentRole();
$gridControl = '';
$folder = ''; //manual edit files in 'live' folder only
$object_type = FHtml::getRequestParam('object_type');

if (FHtml::isInRole('', 'update', $currentRole) && FHtml::config('ADMIN_INLINE_EDIT', true) == true) {
    $gridControl = $folder . '_columns.php';
} else {
    $gridControl = $folder . '_columns.php';
}

?>
<div class="object-setting-index">
    <div class="form">
        <div class="row">
            <div class="profile-sidebar col-md-2">
                <!-- END MENU -->
                <div class="">
                    <?php echo FHtml::buildTabs('object_type', 'nav', 'object_type'); ?>

                </div>
            </div>
            <div class="col-md-10">
                <div class="portlet light">
                    <div class="portlet-title tabbable-line">
                        <div class="caption caption-md">
                            <i class="icon-globe theme-font hide"></i>
                            <span
                                class="caption-subject font-blue-madison uppercase"> <b><?= FHtml::t('common', $object_type) ?> </b> / <?= FHtml::t('common', $moduleTitle) ?></span>
                        </div>
                        <div class="tools pull-right">
                            <a href="#" class="fullscreen"></a>
                            <a href="#" class="collapse"></a>
                        </div>
                        <ul class="nav nav-tabs">
                            <?php if (!empty($object_type)) { ?>
                                <li class="<?= FHtml::currentController() == 'object-type' ? 'active' : '' ?>">
                                    <a href="<?= FHtml::createUrl('system/object-type/update', ['id' => $object_type]) ?>"><?= FHtml::t('common', 'Info') ?></a>
                                </li>
                            <?php } ?>
                            <?php if (!empty($object_type)) { ?>
                                <li class="<?= FHtml::currentController() == 'settings-schema' ? 'active' : '' ?>">
                                    <a href="<?= FHtml::createUrl('system/settings-schema/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Schema') ?></a>
                                </li>
                            <?php } ?>
                            <li class="<?= FHtml::currentController() == 'object-setting' ? 'active' : ''  ?>">
                                <a href="<?= FHtml::createUrl('system/object-setting/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Settings') ?></a>
                            </li>
                            <li class="<?= FHtml::currentController() == 'object-category' ? 'active' : '' ?>">
                                <a href="<?= FHtml::createUrl('system/object-category/index', ['object_type' => $object_type]) ?>"><?= FHtml::t('common', 'Categories') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div id="ajaxCrudDatatable" class="">
                        <?= \common\widgets\FGridView::widget([
                            'id' => 'crud-datatable',
                            //'floatHeader' => true, // enable this will keep header when scroll but disable resizeable column feature
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'object_type' => 'object_category',
                            'pjax' => true,
                            'toolbar' => require(__DIR__ . '/_toolbar.php'),
                            'columns' => require(__DIR__ . '/' . $gridControl),
                            'default_fields' => ['object_type' => FHtml::getRequestParam('object_type')],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'bordered' => true,
                            'layout' => "{toolbar}\n{items}\n{summary}\n{pager}",
                        ]) ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrubModal",
    "footer" => "",
]) ?>
<?php Modal::end(); ?>
