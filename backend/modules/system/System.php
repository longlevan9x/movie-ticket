<?php

namespace backend\modules\system;

use backend\models\AuthMenu;
use common\components\FHtml;
use yii\base\Module;

/**
 * api module definition class
 */
class System extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\system\controllers';

    const FIELDS_GROUP = [ //table.column
    ];

    const LOOKUP = [    // 'table.column' => array(), 'table.column' => 'table1.column1'
    ];

    public static function getLookupArray($column) {
        if (key_exists($column, self::LOOKUP)) {
            $data = self::LOOKUP[$column];

            $data = FHtml::getComboArray($data);

            return $data;
        }


        return [];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public static function createModuleMenu()
    {
        $controller = FHtml::currentController();

        $menu[] = AuthMenu::menuItem(
            '#',
            'Administration',
            'glyphicon glyphicon-th',
            FHtml::isInArray($controller, ['object*', 'settings', 'user', 'auth*', 'application', 'settings-menu']),
            [],
            [
                AuthMenu::menuItem(
                    '/settings/index',
                    'Configuration',
                    'glyphicon glyphicon-cog',
                    $controller == 'settings',
                    [FHtml::ROLE_ADMIN]
                ),
                AuthMenu::menuItem(
                    '/system/object-setting/index',
                    'Settings',
                    'glyphicon glyphicon-book',
                    $controller == 'object-setting',
                    [FHtml::ROLE_ADMIN]
                ),
                AuthMenu::menuItem(
                    '/system/object-category/index',
                    'Categories',
                    'glyphicon glyphicon-book',
                    $controller == 'object-category',
                    [FHtml::ROLE_ADMIN]
                ),
                AuthMenu::menuItem(
                    '/user/index',
                    'Users',
                    'glyphicon glyphicon-cog',
                    $controller == 'user',
                    [FHtml::ROLE_ADMIN]
                ),
                AuthMenu::menuItem(
                    '/auth-group/index',
                    'User Groups',
                    'glyphicon glyphicon-cog',
                    $controller == 'auth-group',
                    [FHtml::ROLE_ADMIN]
                ),
                AuthMenu::menuItem(
                    '/auth-role/index',
                    'User Rights',
                    'glyphicon glyphicon-cog',
                    $controller == 'auth-role',
                    [FHtml::ROLE_ADMIN]
                ),
                AuthMenu::menuItem(
                    '/system/settings-menu/index',
                    'Menus',
                    'glyphicon glyphicon-cog',
                    $controller == 'settings-menu',
                    [FHtml::ROLE_ADMIN]
                ),
                !DYNAMIC_OBJECT_ENABLED ? null : AuthMenu::menuItem(
                    '/system/object-type/index',
                    'Objects',
                    'glyphicon glyphicon-book',
                    $controller == 'object-type',
                    [FHtml::ROLE_ADMIN]
                ),

                !OBJECT_ACTIONS_ENABLED ? null : AuthMenu::menuItem(
                    '/object-actions/index',
                    FHtml::t('common', 'Object Changes Log'),
                    'glyphicon glyphicon-book',
                    $controller == 'object-actions',
                    [FHtml::ROLE_ADMIN]
                ),
                AuthMenu::menuItem(
                    '/system/application/index',
                    FHtml::t('common', 'Applications'),
                    'glyphicon glyphicon-book',
                    $controller == 'applications',
                    [FHtml::ROLE_ADMIN]
                ),

            ]
        );

        return $menu;
    }
}
