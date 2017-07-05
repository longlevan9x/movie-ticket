<?php
namespace backend\modules\app\actions;
use backend\actions\BaseAction;
use backend\modules\app\models\AppUserAPI;
use common\components\FHtml;
use common\components\Response;

class RegisterAction extends BaseAction
{
    public $is_secured = false;

    public function run()
    {
        $username = FHtml::getRequestParam('username');
        $name = FHtml::getRequestParam('name');
        $gender = FHtml::getRequestParam('gender');
        $address = FHtml::getRequestParam('address');
        $phone = FHtml::getRequestParam('phone');
        $password = FHtml::getRequestParam('password');

        if (strlen($username) == 0
            //|| strlen($phone) == 0
            || strlen($name) == 0
            || strlen($password) == 0
        ) {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::MISSING_PARAMS, ['code'=> 202]);
        }

        $check = AppUserAPI::find()->where("username = '".$username."'")->one();

        if(isset($check))
        {
            return Response::getOutputForAPI('', \Globals::ERROR, \Globals::EMAIL_OR_USERNAME_EXIST, ['code'=> 226]);
        }
        else
        {
            $today = date('Y-m-d H:i:s',time());

            $new_user = new AppUserAPI();
            $new_user->name = $name;
            $new_user->email = $username;
            $new_user->username = $username;
            $new_user->gender = $gender;
            $new_user->address = $address;
            $new_user->phone = $phone;
            $new_user->is_active = \Globals::STATE_INACTIVE;
            $new_user->status = \Globals::LABEL_NORMAL;
            $new_user->created_date = $today;
            $new_user->balance = 1000000;

            $reset_token = md5(time());
            $new_user->password_reset_token = $reset_token;
            $new_user->setPassword($password);
            $new_user->generateAuthKey();

            if($new_user->save())
            {
                $send = \Yii::$app->mailer->compose(['html' => 'welcome-html', 'text' => 'welcome-text', 'htmlLayout'=>'@layouts/welcome-html.php'], ['user' => $new_user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($new_user->email)
                    ->setSubject('[iwanadeal] Welcome new member')
                    ->send();
                if($send){
                    return Response::getOutputForAPI('', \Globals::SUCCESS, 'OK', ['code'=> 200]);
                }else{
                    $new_user->delete();
                    return Response::getOutputForAPI('', \Globals::ERROR, 'Can not send activation email, please check your email address', ['code'=> 229]);
                }
            }
            else
            {
                $errors = $new_user->getErrors();
                $error_message = Response::getErrorMessage($errors);
                return Response::getOutputForAPI('', \Globals::ERROR, $error_message, ['code'=> 203]);
            }
        }
    }
}
