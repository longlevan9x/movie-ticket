<?php
$baseUrl = Yii::$app->getUrlManager()->getBaseUrl();
$frontendBaseUrl = \common\components\FHtml::currentBaseURL(FRONTEND);
?>

<!--[if lt IE 9]>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/respond.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/excanvas.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<![endif]-->


<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

<?php //$this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/js.cookie.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/jquery.blockui.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<!-- END CORE PLUGINS -->


<!-- BEGIN THEME GLOBAL SCRIPTS -->
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/global/scripts/app.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<!-- END THEME LAYOUT SCRIPTS -->
<?php $this->registerJsFile($baseUrl . "/js/eModal.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/js/init.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>

<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->

<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/layouts/layout/scripts/layout.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<?php $this->registerJsFile($baseUrl . "/themes/metronic/assets/layouts/layout/scripts/demo.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]) ?>
<!-- END THEME LAYOUT SCRIPTS -->

<!-- END JAVASCRIPTS -->