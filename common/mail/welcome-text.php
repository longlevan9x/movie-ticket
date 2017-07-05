<?php

/* @var $this yii\web\View */
/* @var $user \backend\modules\app\models\AppUserAPI */


?>

    Hello <?= $user->username ?>,

    Welcome to iwanadeal
    Your registration is completed!
    Please click below button to confirm your registration
    <?= Yii::$app->getUrlManager()->getHostInfo().Yii::$app->urlManagerFrontend->createUrl(['index.php/site/confirm-registration', 'email'=>$user->email, 'token'=>$user->password_reset_token]) ?>

    Thank you for using our services,

    iwanadeal