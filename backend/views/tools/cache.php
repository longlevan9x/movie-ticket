<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\components\CrudAsset;
use common\widgets\BulkButtonWidget;
use common\components\FHtml;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\TestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$moduleName = 'Api';
$moduleTitle = 'Api';
$moduleKey = 'api';

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
$key = FHtml::getRequestParam('key');
?>

<div class="test-index">
    <form id="test" method="get" action="<?= FHtml::createUrl('/cache/index') ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-3">Key:</div>
                <div class="col-md-9"><input type="text" name="key" id="key" value="<?= $key ?>" class="form-control"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="action" value="view" class="btn btn-blue">View</button>
                <button type="submit" name="action" value="refresh" class="btn btn-red">Refresh</button>

            </div>
        </div>
    </form>
    <h1>CACHEs </h1>
    <?= FHtml::showArray($model) ?>
</div>