<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28/03/2017
 * Time: 11:31 SA
 */

namespace common\components;

use backend\modules\system\models\Application;
use common\components\FHtml;
use frontend\components\Helper;
use frontend\modules\cms\Cms;
use frontend\modules\travel\TravelHelper;
use Yii;

class FApplication extends Application
{
    public static function getFrontendMenu($controller = '', $action = '', $module = '')
    {
        return Cms::getFrontendMenu($controller, $action);
    }
}