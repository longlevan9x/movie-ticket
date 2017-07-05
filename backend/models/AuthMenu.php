<?php

namespace backend\models;

use backend\modules\system\System;
use common\components\AccessRule;
use common\components\FConstant;
use common\components\FHtml;
use Yii;
use yii\helpers\BaseInflector;
use yii\helpers\Json;

/**
 * @property AuthPermission[] $roles
 */
class AuthMenu extends AuthMenuBase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['icon', 'name', 'route', 'group', 'role', 'is_active'], 'required'],
            [['is_active'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['icon', 'name', 'route'], 'string', 'max' => 255],
            [['group', 'created_user', 'modified_user', 'application_id'], 'string', 'max' => 100],
            [['role'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('auth', 'ID'),
            'icon' => Yii::t('auth', 'Icon'),
            'name' => Yii::t('auth', 'Name'),
            'route' => Yii::t('auth', 'Route'),
            'group' => Yii::t('auth', 'Group'),
            'is_active' => Yii::t('auth', 'Is Active'),
            'created_date' => Yii::t('auth', 'Created Date'),
            'created_user' => Yii::t('auth', 'Created User'),
            'modified_date' => Yii::t('auth', 'Modified Date'),
            'modified_user' => Yii::t('auth', 'Modified User'),
            'application_id' => Yii::t('auth', 'Application ID'),
        ];
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return FHtml::currentDb();
    }

    public static function menuItem($route, $name, $icon, $active, $roles = array(), $children = false)
    {
        /* @var $check AuthMenu */
        if (empty($route))
            return null;
        $object_type = '';
        $module = ''; $controller = ''; $action = '';
        $arr = explode('/', str_replace('/index', '', trim($route, '/')));
        if (count($arr) > 2) {
            $controller = $arr[1];
            $object_type = str_replace('-', '_', $controller);
            $module = BaseInflector::camel2words($arr[0]);
            $action = $arr[2];

        } else if (count($arr) > 1) {
            $controller = $arr[1];
            $object_type = str_replace('-', '_', $controller);
            $module = BaseInflector::camel2words($arr[0]);
            $action = 'index';

        } else if (count($arr) == 1) {
            $controller = $arr[0];
            $object_type = str_replace('-', '_', $controller);
            $module = BaseInflector::camel2words($name);
            $action = 'index';
        }

        if ($route == '#')
            $condition = "url = '$route' and name = '$name'";
        else
            $condition = "url = '$route'";

        $check = AuthMenu::find()->where($condition)->one();
        if (isset($check)) {
            $roles = Json::decode($check->role);

            $menu = array(
                'active' => $active,
                'name' => FHtml::t('common', $check->name),
                'visible' => $check->is_active && AccessRule::checkAccess($roles, $module, $controller, $action),
                'icon' => $check->icon,
                'url' => Yii::$app->urlManager->createUrl([$check->route]),
            );

        } else {
            $menu = array(
                'active' => $active,
                'name' => FHtml::t('common', $name),
                'visible' => AccessRule::checkAccess($roles, $module, $controller, $action),
                'icon' => $icon,
                'url' => Yii::$app->urlManager->createUrl([$route]),
            );

            $now = time();
            //$imageName = '';
            $today = date('Y-m-d H:i:s', $now);
            $new_menu = new AuthMenu();
            $new_menu->icon = $icon;
            $new_menu->name = $name;
            $new_menu->route = $route;
            $new_menu->group = BACKEND;
            $new_menu->is_active = $active;
            $new_menu->role = Json::encode($roles);
            $new_menu->application_id = FHtml::currentApplicationId();
            $new_menu->created_user = (string)FHtml::currentUserId();

            if ($route == '#')
                $new_menu->sort_order = 0;
            else
                $new_menu->sort_order = 1;

            $new_menu->created_date = $today;

            if (count($arr) > 1) {
                $new_menu->object_type = $object_type;
                $new_menu->module = $module;
                if ($new_menu->module == 'System')
                    $new_menu->module = 'Administration';
            } else if (count($arr) == 1) {
                if ($name == 'Home') {
                    $new_menu->module = 'Home';
                    $new_menu->object_type = '';
                }
                else if ($route !== '#')
                    $new_menu->module = 'Administration';
                else
                    $new_menu->module = $name == 'Administration' ? 'Administration' : $module;
            }
            $new_menu->save();
        }

        if (!($children === false)) {
            $menu['children'] = $children;
        }
        return $menu;
    }

    /**
     * Connections
     */
    public function getRoles()
    {
        return $this->hasMany(AuthPermission::className(), ['object_id' => 'id'])
            ->andOnCondition(['AND',
                ['relation_type' => 'menu-role'],
                ['object2_type' => 'auth_menu'],
                ['object_type' => 'auth_role']
            ]);
    }

    public static function buildDashBoardMenu()
    {
        $controller = FHtml::currentController();

        return AuthMenu::menuItem(
            'site/index',
            'Home',
            'fa fa-list',
            $controller == 'site',
            ['content']
        );
    }

    public static function buildAdministrationMenu()
    {
        $currentRole = FHtml::getCurrentRole();
        if ($currentRole != User::ROLE_ADMIN)
            return null;

        $menu = System::createModuleMenu();

        return $menu;
    }

    public static function buildToolsMenu()
    {
        $controller = FHtml::currentController();
        $action = FHtml::currentAction();
        $currentRole = FHtml::getCurrentRole();
        if ($currentRole != User::ROLE_ADMIN)
            return null;

        $menu = array(
            'active' => FHtml::isInArray($controller, ['tools*']),
            'name' => Yii::t('common', 'Tools'),
            'icon' => 'glyphicon glyphicon-wrench',
            'url' => FHtml::createUrl('/tools/index'),
            'children' => array(
                array(
                    'label' => Yii::t('common', 'Api'),
                    'active' => in_array($controller, ['tools']) AND ($action == 'api'),
                    'url' => FHtml::createUrl('tools/api'),
                    'icon' => '',
                ),
                array(
                    'label' => Yii::t('common', 'Cache'),
                    'active' => $controller == 'tools' AND ($action == 'cache'),
                    'url' => FHtml::createUrl('tools/cache'),
                    'icon' => '',
                ),
                array(
                    'label' => Yii::t('common', 'Phpmyadmin'),
                    'active' => in_array($controller, ['tools']) AND ($action == 'api'),
                    'url' => FHtml::currentDomain() . '/phpmyadmin',
                    'icon' => '',
                ),
                array(
                    'label' => Yii::t('common', 'Swagger (API test)'),
                    'active' => in_array($controller, ['tools']) AND ($action == 'api'),
                    'url' => FHtml::currentDomain() . '/swagger',
                    'icon' => '',
                ),
            )
        );

        return $menu;
    }
}
