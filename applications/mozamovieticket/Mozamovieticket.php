<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28/03/2017
 * Time: 11:31 SA
 */

namespace applications\mozamovieticket;

use backend\models\AuthMenu;
use backend\modules\app\App;
use backend\modules\cinema\Cinema;
use backend\modules\booking\Booking;
use backend\modules\system\System;
use common\components\FApplication;
use common\components\FHtml;
use frontend\components\Helper;

use Yii;

class Mozamovieticket extends FApplication
{
    public static function lang() {
        return '';
    }


    public static function getBackendMenu($controller = '', $action = '', $module = '') {
        $menu[] = AuthMenu::buildDashBoardMenu();

        $menu = array_merge($menu, Cinema::createModuleMenu());

        $menu = array_merge($menu, Booking::createModuleMenu());

        $menu = array_merge($menu, App::createModuleMenu());

        $menu = array_merge($menu, System::createModuleMenu());
        return $menu;
    }

    public static function getFrontendMenu($controller = '', $action = '', $module = '')
    {
        return array();
    }


}