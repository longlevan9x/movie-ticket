<?php
namespace backend\actions;

use backend\modules\app\models\AppUserTokenAPI;
use common\components\FHtml;
use common\components\Response;

class UpdateLocationAction extends BaseAction
{
    public function run()
    {
        $token = FHtml::getRequestParam('token', '');
        $lat = FHtml::getRequestParam('lat', '');
        $long = FHtml::getRequestParam('long', '');

        if (strlen($token) != 0) {
            $login_token = AppUserTokenAPI::find()->where('token = "' . $token . '"')->one();
            if (isset($login_token) && isset ($login_token->user)) {
                $login_token->user->lat = $lat;
                $login_token->user->long = $long;
                $login_token->user->save();
            }
            return Response::getOutputForAPI('', \Globals::SUCCESS, 'OK', ['code' => 200]);
        } else {
            return Response::getOutputForAPI('', \Globals::ERROR, 'FAIL', ['code' => 201]);
        }
    }
}
