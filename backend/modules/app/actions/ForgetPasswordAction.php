<?php
namespace backend\modules\app\actions;

use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use common\components\Response;
use Yii;

class ForgetPasswordAction extends BaseAction
{

    public function run()
    {
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';

        if (strlen($email) == 0) {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::MISSING_PARAMS, ['code' => 202]);
        }

        $checkEmail = AppUserAPI::find()->where("email = '" . $email . "'")->one();

        if (!isset($checkEmail)) {

            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::EMAIL_OR_USERNAME_DOES_NOT_EXIST, ['code'=> 224]);

        }
        /* @var AppUserAPI $checkEmail*/
        $token = md5(time().$checkEmail->id);
        $checkEmail->password_reset_token = $token;
        $checkEmail->save();

        $send = Yii::$app->mailer->compose(['html' => 'forget-html', 'text' => 'forget-text'], ['token' => $token])
            ->setFrom('fruity.tester@gmail.com')
            ->setTo($email)
            ->setSubject('iwanadeal - Reset Password Token')
            ->send();
        if($send)
        {
            return Response::getOutputForAPI('', \Globals::SUCCESS, 'OK');
        }
        else
        {
            return Response::getOutputForAPI('', \Globals::ERROR, 'Fail to send email, please check your email address', ['code'=> 229]);
        }

    }

}
