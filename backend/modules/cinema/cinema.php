<?php

namespace backend\modules\cinema;

use backend\models\AuthMenu;
use common\components\FHtml;

/**
 * cinema module definition class
 */
class Cinema extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\cinema\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    const FIELDS_GROUP = [ //table.column
    ];

    const LOOKUP = [    // 'table.column' => array(), 'table.column' => 'table1.column1'

    ];

    public static function getLookupArray($column)
    {
        if (key_exists($column, self::LOOKUP)) {
            $data = self::LOOKUP[$column];

            $data = FHtml::getComboArray($data);

            return $data;
        }

        return [];
    }

    public static function createModuleMenu($menu = ['cinema*', 'cinema-actor', 'cinema-brand', 'cinema-hall', 'cinema-movie', 'cinema-show'])
    {
        $controller = FHtml::currentController();

        $menu[] = AuthMenu::menuItem(
            '#',
            'Cinema',
            'glyphicon glyphicon-cd',
            FHtml::isInArray($controller, $menu),
            [],
            [
                !FHtml::isInArray('cinema', $menu) ? null : AuthMenu::menuItem(
                    '/cinema/cinema/index',
                    'Cinema',
                    '',
                    $controller == 'cinema',
                    []
                ),
                !FHtml::isInArray('cinema-brand', $menu) ? null : AuthMenu::menuItem(
                    '/cinema/cinema-brand/index',
                    'Brand',
                    '',
                    $controller == 'cinema-brand',
                    []
                ),
                !FHtml::isInArray('cinema-actor', $menu) ? null : AuthMenu::menuItem(
                    '/cinema/cinema-actor/index',
                    'Actor',
                    '',
                    $controller == 'cinema-actor',
                    []
                ),
                !FHtml::isInArray('cinema-hall', $menu) ? null : AuthMenu::menuItem(
                    '/cinema/cinema-hall/index',
                    'Hall',
                    '',
                    $controller == 'cinema-hall',
                    []
                ),
                !FHtml::isInArray('cinema-movie', $menu) ? null : AuthMenu::menuItem(
                    '/cinema/cinema-movie/index',
                    'Movie',
                    '',
                    $controller == 'cinema-movie',
                    []
                ),
                !FHtml::isInArray('cinema-show', $menu) ? null : AuthMenu::menuItem(
                    '/cinema/cinema-show/index',
                    'Show',
                    '',
                    $controller == 'cinema-show',
                    []
                ),
            ]
        );

        return $menu;
    }
}
