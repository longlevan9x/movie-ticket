<?php

namespace backend\modules\booking;

use backend\models\AuthMenu;
use common\components\FHtml;

/**
 * booking module definition class
 */
class Booking extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\booking\controllers';

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

    public static function createModuleMenu($menu = ['booking*', 'booking-schedule', 'booking-seat'])
    {
        $controller = FHtml::currentController();

        $menu[] = AuthMenu::menuItem(
            '#',
            'booking',
            'glyphicon glyphicon-th',
            FHtml::isInArray($controller, $menu),
            [],
            [
                !FHtml::isInArray('booking', $menu) ? null : AuthMenu::menuItem(
                    '/booking/booking/index',
                    'Booking',
                    'glyphicon glyphicon-wrench',
                    $controller == 'booking',
                    []
                ),
                !FHtml::isInArray('booking-schedule', $menu) ? null : AuthMenu::menuItem(
                    '/booking/booking-schedule/index',
                    'Schedule',
                    'glyphicon glyphicon-wrench',
                    $controller == 'booking-schedule',
                    []
                ),
                !FHtml::isInArray('booking-seat', $menu) ? null : AuthMenu::menuItem(
                    '/booking/booking-seat/index',
                    'Seat',
                    'glyphicon glyphicon-wrench',
                    $controller == 'booking-seat',
                    []
                ),
            ]
        );

        return $menu;
    }
}
