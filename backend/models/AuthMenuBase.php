<?php

namespace backend\models;

use backend\modules\system\models\SettingsMenu;
use common\models\BaseModel;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $icon
 * @property string $name
 * @property string $route
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property string $group
 * @property string $role
 * @property integer $is_active
 * @property string $created_date
 * @property string $created_user
 * @property string $modified_date
 * @property string $modified_user
 * @property string $application_id
 */
class AuthMenuBase extends SettingsMenu
{
    const GROUP_FRONTEND = FRONTEND;
    const GROUP_BACKEND = BACKEND;

    /**
    * @inheritdoc
    */
    public $tableName = 'settings_menu';

    public static function tableName()
    {
        return 'settings_menu';
    }

    public function getRoute() {
        return $this->url;
    }

    public function setRoute($value) {
        $this->url = $value;
    }
}
