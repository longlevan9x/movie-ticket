<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use common\components\FHtml;

?>

<div class="site-error">
    <h1> LOGS </h1>

    <?= FHtml::currentLog() ?>

    <a class="btn btn-md btn-success" href="<?= FHtml::createUrl('site/log', ['action' => 'clear']) ?>">Clear</a>
</div>
