<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/20/2016
 * Time: 5:08 PM
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $token */
?>
<div class="forget-password">
    <p>Hi,</p>
    <p>Your reset password link is : <i> <?=  Html::a('Change password page', Yii::$app->getUrlManager()->getHostInfo().Yii::$app->urlManagerFrontend->createUrl('index.php/site/change-password')) ?>
        </i></p>
    <p>Please use this token for reset password : <?= $token ?> </p>

    <p>BR,</p>
    <p>iwanadeal System</p>
</div>