<?php
/**
 * Created by PhpStorm.
 * User: HY
 * Date: 1/4/2016
 * Time: 3:33 PM
 */

namespace common\widgets;

use yii\web;

class FUploadedFile extends web\UploadedFile
{
    public $oldName;
    public $fieldName;
}