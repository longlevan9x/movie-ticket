<?php
/**
 * Created by PhpStorm.
 * User: Darkness
 * Date: 11/30/2016
 * Time: 2:00 PM
 */

namespace common\components;


class Setting extends FHtml
{
    public static function getSettingValueByKey($key, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '') {
        return self::config($key, $default_value, $params, $group, $editor, $lookup);
    }
}