<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\components;

use backend\assets\CustomAsset;
use backend\models;
use common\widgets\BaseWidget;
use common\widgets\FCKEditor;
use common\widgets\FFileInput;
use common\widgets\fheadline\FHeadline;
use common\widgets\FListView;
use Faker\Provider\DateTime;
use frontend\models\ViewModel;
use himiklab\thumbnail\EasyThumbnailImage;
use kartik\alert\Alert;
use kartik\alert\AlertBlock;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\dropdown\DropdownX;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\slider\Slider;
use kartik\widgets\Growl;
use kartik\widgets\InputWidget;
use kartik\widgets\Select2;
use kartik\widgets\StarRating;
use kartik\widgets\SwitchInput;
use kartik\widgets\TimePicker;
use xj\jplayer\AudioWidget;
use xj\jplayer\VideoWidget;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidValueException;
use yii\base\ViewContextInterface;
use yii\base\Widget;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\JsExpression;
use common\widgets\FUploadedFile;
use yii\web\View;
use yii\widgets\MaskedInput;
use backend\modules\system;
use backend\modules\system\models\ObjectSetting;

class FHtml extends FApi
{
    public static function button($type, $style, $htmlOptions = array(), $isEditable = TRUE)
    {
        if (empty($type) || empty($style) || !array_key_exists($style, self::$buttonIcons))
            return self::showEmpty();
        if (isset($htmlOptions['class']))
            $htmlOptions['class'] = $htmlOptions['class'] . ' btn btn-' . $style;
        else
            $htmlOptions['class'] = 'btn btn-' . $style;
        if (!$isEditable)
            $htmlOptions['class'] .= ' disabled';
        $html = '<button type="' . $type . '" ' . self::renderAttributes($htmlOptions) . '>';
        $html .= '  <i class="' . self::$buttonIcons[$style] . '"></i>';
        if (isset($htmlOptions['value'])) {
            $html .= '  ' . $htmlOptions['value'];
        } else {
            $html .= '  ' . self::buttonValue($style);
        }
        $html .= '</button>';
        return $html;
    }

    public static function showEmpty()
    {
        $str = '<span style=" font-style: italic" class="text muted">' . Yii::t('common', 'title.empty') . '</span>';
        return $str;
    }

    public static function renderAttributes($attributes = array())
    {
        $html = "";
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . $value . '" ';
        }
        return $html;
    }

    private static function buttonValue($style)
    {
        return FHtml::t('common', $style);
    }


    public static function submitButton($content = 'Submit', $options = [])
    {
        $options['type'] = 'submit';
        return Html::button($content, $options);
    }

    //2017/03/28
    public static function a($text, $url = null, $options = [], $keep_current_params = ['id'])
    {
        if ($url == [''])
            $keep_current_params = false;


        if (is_array($keep_current_params)) {
            $excluded_params = $keep_current_params;
            if (is_array($url)) {
                foreach ($url as $key => $value)
                    if (is_string($key))
                        $excluded_params[] = $key;
            }
            $params = FHtml::RequestParams($excluded_params);
        } else if ($keep_current_params == true)
            $params = FHtml::RequestParams(['id']);
        else
            $params = [];

        if (!isset($url) || empty($url))
            $url = FHtml::currentUrlPath();

        if (!empty($params))
            $url = ArrayHelper::merge($url, $params);

        return Html::a($text, $url, $options);
    }

    public static function getImageUrl2($image, $model_dir = false, $position = false)
    {
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        } else {

            $baseUpload = Yii::getAlias('@' . UPLOAD_DIR);

            if ($position != FRONTEND) {

                $base_url = Url::base(true);
                $image_path = is_file($baseUpload . DS . $model_dir . DS . $image) ? $base_url . '/' . UPLOAD_DIR . '/' . $model_dir . '/' . $image : $base_url . '/' . UPLOAD_DIR . '/' . WEB_DIR . '/' . \Globals::NO_IMAGE;
                return $image_path;

            } else {

                $base_url = \Yii::$app->urlManagerBackend->baseUrl;

                $image_path = $base_url . '/' . UPLOAD_DIR . '/' . $model_dir . '/' . $image;

                if (!is_file($baseUpload . DS . $model_dir . DS . $image)) {
                    $image_path = $base_url . '/' . UPLOAD_DIR . '/' . WEB_DIR . '/' . \Globals::NO_IMAGE;
                }

                return $image_path;
            }
        }
    }

    public static function showEmptyResult()
    {
        $str = '<span style=" font-style: italic" class="text muted">' . Yii::t('common', 'title.noResult') . '</span>';
        return $str;
    }

    public static function dynamicButton($type, $style, $text, $htmlOptions = array())
    {
        if (empty($type) || empty($style) || !array_key_exists($style, self::$buttonIcons))
            return self::showEmpty();
        if (isset($htmlOptions['class']))
            $htmlOptions['class'] = $htmlOptions['class'] . ' btn btn-' . $style;
        else
            $htmlOptions['class'] = 'btn btn-' . $style;
        $html = '<button type="' . $type . '" ' . self::renderAttributes($htmlOptions) . '>';
        $html .= '  <i class="' . self::$buttonIcons[$style] . '"></i>';
        $html .= '  ' . $text;
        $html .= '</button>';
        return $html;
    }

    public static function buttonSubmit($style, $htmlOptions = array(), $isSmall = FALSE, $isEditable = TRUE, $isShowtext = TRUE)
    {
        $type = self::SUBMIT_TYPE;
        if (empty($type) || empty($style) || !array_key_exists($style, self::$buttonIcons))
            return;
        if (isset($htmlOptions['class']))
            $htmlOptions['class'] = $htmlOptions['class'] . ' btn btn-' . $style;
        else
            $htmlOptions['class'] = 'btn btn-' . $style;
        if ($isSmall)
            $htmlOptions['class'] .= ' mini';
        if (!$isEditable)
            $htmlOptions['class'] .= ' disabled';
        $html = '<button type="' . $type . '" ' . self::renderAttributes($htmlOptions) . '>';
        $html .= '  <i class="' . self::$buttonIcons[$style] . '"></i>';
        if ($isShowtext)
            $html .= '  ' . self::buttonValue($style);
        $html .= '</button>';
        return $html;
    }

    public static function buttonCreate($icon = '<i class="glyphicon glyphicon-plus"></i>', $title = 'Create', $action = 'create', $color = 'success') {
        $button = self::buttonAction($icon, $title, $action, $color);

        return $button;
    }

    public static function buttonDeleteAll($icon = '<i class="glyphicon glyphicon-refresh"></i>', $title = 'Reset', $action = 'delete-all', $color = 'warning') {
        $button = self::buttonAction($icon, $title, $action, $color, true);

        return $button;
    }

    public static function buttonDeleteBulk($icon = '<i class="glyphicon glyphicon-trash"></i>', $title = '', $action = 'bulk-delete', $color = 'danger') {
        $button = self::buttonAction($icon, $title, $action, $color, 'bulk');

        return $button;
    }

    public static function buttonBulkActions($items) {
        $bulkActionButton = '<div class="dropdown pull-left">&nbsp;<button class="btn btn-default" data-toggle="dropdown">'. FHtml::t('common', 'Actions'). '</button>' . DropdownX::widget([
                'items' => $items

            ]). '</div>';

        return $bulkActionButton;
    }

    public static function buttonAction($icon = '', $title = '', $action = '', $color = '', $confirm = false) {
        $view = FHtml::currentView();

        $button = FHtml::a($icon . '&nbsp;' . FHtml::t('common', $title), [$action],
            $confirm ?
                [
                    "class" => "btn btn-$color",
                    'role' => is_bool($confirm) ? 'modal-remote' : "modal-remote-bulk",
                    'data-confirm' => false, 'data-method' => false,// for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => FHtml::t('common', $title),
                    'data-confirm-message' => FHtml::t('common', 'Are you sure') . ' ?',
                    'style' => 'float:left; margin-right:10px;'
                ] :
            [
                'role' => $view->params['editType'],
                'data-pjax' =>  $view->params['isAjax'] == true ? 1 : 0,
                'title' => FHtml::t('common', $title),
                'class' => 'btn btn-' . $color,
                'style' => 'float:left; margin-right:10px;'
            ]);

        return $button;
    }

    public static function buildChangeFieldMenu($table, $field, $action = 'change')
    {
        return self::buildBulkActionsMenu('', '', $table, $field, $action);
    }

    public static function buildBulkActionsMenu($label, $key, $table, $field, $action = 'change')
    {
        if (empty($label))
            $label = FHtml::getFieldLabel($table, $field);

        if (in_array($action, ['change'])) {
            $items = FHtml::getComboArray($key, $table, $field);
            if (!key_exists('', $items))
                $items = ArrayHelper::merge(['' => FHtml::t('common', 'Empty')], $items);
            $child = array();

            foreach ($items as $id => $name) {
                $child[] = '<li>' . FHtml::a($name . '<br/><small class="text-default">\'' . $id . '\'</small>',
                        ["bulk-action", "action" => $action, "field" => $field, "value" => $id],
                        [
                            'role' => 'modal-remote-bulk',
                            'data-confirm' => false, 'data-method' => false,// for overide yii data api
                            'data-request-method' => 'post',
                            'data-confirm-title' => FHtml::t('common', $action),
                            'data-confirm-message' => FHtml::t('common', 'Are you sure') . ' ?',
                        ]) . '</li>';
            }
            if (count($child) == 0)
                return null;

            $result = ['label' => $label, 'items' => $child];
        } else {
            $result = '<li>' . FHtml::a('<i class="glyphicon glyphicon-file"></i> ' . $label,
                    ["bulk-action", "action" => $action, "field" => $field],
                    [
                        'role' => 'modal-remote-bulk',
                        'data-confirm' => false, 'data-method' => false,// for overide yii data api
                        'data-request-method' => 'post',
                        'data-confirm-title' => FHtml::t('common', $action),
                        'data-confirm-message' => FHtml::t('common', 'Are you sure') . ' ?',
                    ]) . '</li>';
        }

        return $result;
    }

    public static function buildBulkDeleteMenu()
    {
        return self::buildBulkActionMenu('<i class="glyphicon glyphicon-trash"></i> ', 'Delete', 'bulk-delete', 'color:red', 'bulk');
    }

    public static function buildDeleteAllMenu()
    {
        return self::buildBulkActionMenu('<i class="glyphicon glyphicon-refresh"></i> ', 'Reset', 'delete-all', 'color:red', true);
    }

    public static function buildBulkActionMenu($icon = '', $title = '', $action = '', $style = '', $confirm = false)
    {
        return '<li>' . FHtml::a($icon . FHtml::t('common', $title),
                [$action],
                $confirm ?
                    [
                        'role' => is_bool($confirm) ? 'modal-remote' : "modal-remote-bulk",
                        'data-confirm' => false, 'data-method' => false,// for overide yii data api
                        'data-request-method' => 'post',
                        'data-confirm-title' => FHtml::t('common', 'Confirmation'),
                        'data-confirm-message' => FHtml::t('common', 'Are you sure'),
                        'style' => $style
                    ] : [
                    'role' => '',
                    'data-confirm' => false, 'data-method' => false,// for overide yii data api
                    'data-request-method' => 'post',
                    'style' => $style
                ] ) . '</li>';
    }

    public static function buildPopulateMenu()
    {
        return '<li>' . FHtml::a('<i class="glyphicon glyphicon-refresh"></i> ' . FHtml::t('common', 'Populate'),
                ["populate"],
                [

                ]) . '</li>';
    }

    public static function buildBulkDividerMenu()
    {
        return '<li class="divider"></li>';
    }


    public static function showLink($text = '', $htmlOptions = array(), $icon = '')
    {
        $html = '<a ' . self::renderAttributes($htmlOptions) . '>';
        if (!empty($icon))
            $html .= '<i class="' . $icon . '"></i> ';
        $html .= $text;
        $html .= '</a>';
        return $html;
    }

    public static function showImageThumbnail($image, $width = 50, $model_dir = PRODUCT_DIR)
    {
        return self::showImage($image, $model_dir, $width, 0);
    }

    public static function showImage($image, $model_dir = '', $width = '100%', $height = 0, $css = '', $title = '', $show_empty_image = TRUE, $hover_effect = '', $linkurl = '')
    {
        if (is_object($image))  // if pass model as parameter
        {
            $image_file = FHtml::getFieldValue($image, ['image', 'logo', 'banner', 'avatar']);
            $model_dir = empty($model_dir) ? self::getImageFolder($image) : $model_dir;
            return self::showImage($image_file, $model_dir, $width, $height, $css, $title, $show_empty_image, $hover_effect, $linkurl);
        }

        $str = '';
        $fileLink = '';

        if (empty($title))
            $title = $image;


        if (strlen($image) > 0) {
            if (StringHelper::endsWith($image, '.mp3')) {
                return AudioWidget::widget([
                    'mediaOptions' => [
                        'mp3' => self::getFileURL($image, $model_dir),
                        'title' => $title
                    ],
                ]);
            }

            if (StringHelper::endsWith($image, '.mp4')) {
                //return '<div class="responsive-video"> <iframe width="100%" height=""  webkitAllowFullScreen mozallowfullscreen allowFullScreen scrolling="no" frameborder="0" src="' . $image . '?autoplay=0"></iframe></div>';

                return VideoWidget::widget([
                    'skinAsset' => 'xj\jplayer\skins\VideoAssets', //OR xj\jplayer\skins\BlueAssets
                    // 'tagClass' => 'jp-video jp-video-270p',

                    'mediaOptions' => [
                        'mp4' => self::getFileURL($image, $model_dir),
                        'title' => $title
                    ],
                    'jsOptions' => [
                        'supplied' => "webmv, ogv, m4v,mp4",
                        'size' => [
                            'width' => "100%",
                            'height' => "auto",
                        ],
                        'smoothPlayBar' => true,
                        'keyEnabled' => true,
                        'remainingDuration' => true,
                        'toggleDuration' => true
                    ],
                ]);
            }

            if (StringHelper::endsWith($image, '.m4v')) {
                //return '<div class="responsive-video"> <iframe width="100%" height=""  webkitAllowFullScreen mozallowfullscreen allowFullScreen scrolling="no" frameborder="0" src="' . $image . '?autoplay=0"></iframe></div>';

                return VideoWidget::widget([
                    'skinAsset' => 'xj\jplayer\skins\VideoAssets', //OR xj\jplayer\skins\BlueAssets
                    //'tagClass' => 'jp-video jp-video-270p',

                    'mediaOptions' => [
                        'm4v' => self::getFileURL($image, $model_dir),
                        'title' => $title
                    ],
                    'jsOptions' => [
                        'supplied' => "webmv, ogv, m4v,mp4",
                        'size' => [
                            'width' => "100%",
                            'height' => "auto",
                        ],
                        'smoothPlayBar' => true,
                        'keyEnabled' => true,
                        'remainingDuration' => true,
                        'toggleDuration' => true
                    ],
                ]);
            }

            if (strpos($image, 'api.soundcloud.com') !== false) {
                $image = str_replace('https://', 'https%3A//', $image);
                return ' <iframe width="100%" height="" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=' . $image . '&amp;auto_play=false&amp;hide_related=false&amp;visual=true"></iframe>';

            } else if (strpos($image, 'vimeo.com') !== false) {
                return '<div class="responsive-video"> <iframe width="100%" height=""  webkitAllowFullScreen mozallowfullscreen allowFullScreen scrolling="no" frameborder="0" src="' . $image . '"></iframe></div>';

            } else if (strpos($image, 'youtube.com') !== false) {
                if (!StringHelper::startsWith('//', $image))
                    $image = '//' . $image;
                return '<div class="responsive-video"> <iframe width="100%" height=""  webkitAllowFullScreen mozallowfullscreen allowFullScreen scrolling="no" frameborder="0" src="' . $image . '"></iframe></div>';
            } else if (strpos($image, 'http:') !== false || strpos($image, 'https:') !== false || strpos($image, 'www.') !== false) {
                $imageLink = $image;
            } else {
                $baseUpload = FHtml::getFullUploadFolder();

                if (StringHelper::startsWith($image, '/')) {
                    $imageLink = trim($baseUpload . $image);
                } else {
                    $imageLink = trim($baseUpload . DS . $model_dir . DS . $image);
                }

                if (is_file($imageLink)) {

                    $fileLink = $imageLink;
                    if (is_numeric($width) && is_numeric($height)) {
                        $imageLink = self::getFileURL($image, $model_dir);

//                        $str = EasyThumbnailImage::thumbnailImg(
//                            $imageLink,
//                            $width,
//                            $height,
//                            EasyThumbnailImage::THUMBNAIL_INSET,
//                            ['alt' => $title, 'class' => $css]);
                        //return $str;
                    } else {

                        $imageLink = self::getFileURL($image, $model_dir);

                    }
                } else {
                    $imageLink = FHtml::defaultImage($show_empty_image, $width, $height, $model_dir);
                }
            }
        } else {
            $imageLink = FHtml::defaultImage($show_empty_image, $width, $height, $model_dir);
        }
        if (empty($str)) {
            if (strlen($imageLink) > 0) {
                $str .= '<img src="' . $imageLink . '"';

                if (!empty($width) || !empty($height)) {
                    if (!empty($width)) {
                        $str = $str . ' width="' . $width . '" ';
                    }
                    if (!empty($height)) {
                        $str = $str . ' height="' . $height . '" ';
                    }

                    if (!empty($css)) {
                        if (strpos($css, ':') !== false) {
                            $str = $str . ' style="' . $css . '" ';
                        } else if ($css != 'img-responsive') {
                            $str = $str . ' class="' . $css . '" ';
                        }
                    }
                } else {
                    $str = $str . ' class="img-responsive ' . $css . '" ';
                }

                if ($title !== '') {
                    $str = $str . ' alt="' . $title . '" ';
                }

                $str .= ' />';

            }
        }

        if (empty($linkurl))
            $linkurl = $imageLink;

//        $zone = FHtml::currentZone();
//        if (empty($hover_effect) && $zone == BACKEND) //if in admin
//            $hover_effect = 'hovereffect1';

        if (!empty($hover_effect) && ($hover_effect !== 'none') && !(is_numeric($width) && is_numeric($height)) && strpos($imageLink, DEFAULT_IMAGE) === false) {
            $str = '<div class="' . $hover_effect . '">' . $str . '<div class="overlay"> <h2>' . $title . '</h2> <a class="info" target="_blank" href="' . $linkurl . '">OPEN</a> </div></div>';
        }
        return $str;
    }

    public static function getFileURL($image, $model_dir = '', $position = false, $default_file = \Globals::NO_IMAGE)
    {
        if ($position === false)
            $position = FHtml::currentZone();

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        } else {
            if (StringHelper::startsWith($model_dir, 'www/')) { // common folder for all applications
                $baseUpload = Yii::getAlias('@' . UPLOAD_DIR);
                $base_url = \Yii::$app->urlManager->baseUrl . '/' . BACKEND . '/' . WEB_DIR;
                $image_path = empty($default_file) ? '' : is_file($baseUpload . DS . $model_dir . DS . $image) ? $base_url . '/' . UPLOAD_DIR . '/' . $model_dir . '/' . $image : $base_url . '/' . UPLOAD_DIR . '/' . WEB_DIR . '/' . $default_file;
                return $image_path;
            }
            $baseUpload = FHtml::getFullUploadFolder('', $position);

            if ($position != FRONTEND) {
                $base_url = Url::base(true);
                $image_path = empty($default_file) ? '' : is_file($baseUpload . DS . $model_dir . DS . $image) ? $base_url . '/' . UPLOAD_DIR . '/' . $model_dir . '/' . $image : $base_url . '/' . UPLOAD_DIR . '/' . WEB_DIR . '/' . $default_file;

                $image_path = FHtml::getApplicationUploadFolder($image_path, $position);

                return $image_path;

            } else {
                $base_url = \Yii::$app->urlManager->baseUrl . '/' . BACKEND . '/' . WEB_DIR;
                $image_path = $base_url . '/' . UPLOAD_DIR . '/' . $model_dir . '/' . $image;

                if (!is_file($baseUpload . DS . $model_dir . DS . $image)) {
                    $image_path = empty($default_file) ? '' : $base_url . '/' . UPLOAD_DIR . '/' . WEB_DIR . '/' . $default_file;
                }

                $image_path = FHtml::getApplicationUploadFolder($image_path, $position);

                return $image_path;
            }
        }
    }

    public static function defaultImage($show_empty_image = TRUE, $width = 50, $height = 50, $moder_dir = '')
    {
        $file = '';
        $baseUploadFolder = self::baseUploadFolder();
        if ($show_empty_image === TRUE || $show_empty_image === 1) {
            if (strpos($width, '%') > 0)
                $width = 150;

            if (!empty($moder_dir)) {
                $file = WWW_DIR . '/' . str_replace('image', $moder_dir, DEFAULT_IMAGE);
                if (is_file(UPLOAD_DIR . '/' . $file)) {
                    $file = $baseUploadFolder . '/' . $file;
                } else
                    $file = $baseUploadFolder . '/' . WWW_DIR . '/' . DEFAULT_IMAGE;

            } else {
                $file = $baseUploadFolder . '/' . WWW_DIR . '/' . DEFAULT_IMAGE;
            }
        } else if ($show_empty_image === FALSE || $show_empty_image === 0) {
            return '';
        } else if (is_string($show_empty_image) && !empty($show_empty_image)) {
            if (strpos($width, '%') > 0)
                $width = 150;
            $file = $baseUploadFolder . '/' . WWW_DIR . '/' . $show_empty_image;
        }
        //self::var_dump($file); die;
        return $file;
    }

    public static function getColorLabel($color)
    {
        return '<span class="label label-sm" style="background: ' . $color . '">' . $color . '</span>';
    }

    public static function currentBaseURL($position = FRONTEND)
    {
        if ($position == FRONTEND) {
            $base_url = str_replace(BACKEND . '/web', '', Url::base(true));
        } else {
            $base_url = \Yii::$app->urlManager->baseUrl;
        }

        return $base_url;
    }

    public static function generateCode($userId)
    {
        $s = strtoupper(md5(uniqid(rand(), true)));
        //return substr($s . $userId,18,strlen($s));
        return substr($s . $userId, 18, 6);
    }

    public static function generateRandomCode($length)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        return strtoupper($result);
    }

    public static function var_dump($object, $condition = true)
    {
        if ($condition) {
            echo '<code><pre>';
            print_r($object);
            echo '</pre></code>';
        }
    }

    public static function beautyResponse($array)
    {
        $array = array_map(function ($v) {
            return (!is_null($v)) ? is_array($v) ? array_map(function ($v) {
                return (!is_null($v)) ? $v : "";
            }, $v) : $v : "";
        }, $array);
        return $array;
    }

    public static function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[FHtml::crypto_rand_secure(0, $max)];
        }
        return $token;
    }

    public static function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int)($log / 8) + 1; // length in bytes
        $bits = (int)$log + 1; // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    public static function flushCachedText($key = '')
    {
        $key = empty($key) ? FHtml::getCachedKey('Settings_Text') : $key;
        FHtml::deleteCachedData($key);
    }

    public static function getCachedText($key = '')
    {
        $key = empty($key) ? FHtml::getCachedKey('Settings_Text') : $key;
        $textArray = [];
        $textArray = FHtml::getCachedData($key);
        if (!isset($textArray)) {
            $textModels = FHtml::getModels('settings_text');
            $textArray = [];
            foreach ($textModels as $model) {

                $textArray = array_merge($textArray, [$model->name => $model->toArray()]);
            }
            FHtml::saveCachedData($textArray, $key);
        }
        return $textArray;
    }


    public static function getImageUrl($image, $model_dir = false, $position = false)
    {
        return self::getFileURL($image, $model_dir, $position);
    }

    public static function showCurrentUserAvatar($width = '', $height = '50', $css = 'img-circle')
    {
        return FHtml::showImage(self::currentUserAvatar(), 'user', $width, $height, $css, '', true, 'none');
    }

    public static function showCurrentUser()
    {
        return self::showUser(self::currentUser());
    }

    //2017.4.18
    public static function showUser($userid, $keyField = '', $displayField = 'name', $link_url = '')
    {
        $result = self::getUserName($userid, $keyField, $displayField);
        return $result;
    }

    public static function getUserName($userid, $keyField = '', $displayField = 'username')
    {
        if (!isset($userid))
            return '';

        if (empty($keyField)) {
            if (is_numeric($userid))
                $keyField = 'id';
            else if (is_string($userid))
                $keyField = 'username';
            else
                $keyField = 'id';
        }

        if (empty($displayField))
            $displayField = 'username';

        if (is_object($userid)) { //if pass $model as $userid
            $result = FHtml::getFieldValue($userid, $displayField);
        } else {


            $sql_select = '*';
            $sql_table = 'user';
            $query = new Query;
            $query->select($sql_select)
                ->from($sql_table);
            $query->andWhere([$keyField => $userid]);
            $data = $query->one();

            if (isset($data))
                $result = $data[$displayField];
            else
                $result = $userid;
        }

        return $result;
    }

    public static function createUrl($url, $params = [], $position = BACKEND)
    {
        if (is_array($url)) {
            if ($position == BACKEND) {
                $result = Yii::$app->urlManager->createUrl(ArrayHelper::merge($url, $params));
            }
            else {
                $result = Yii::$app->urlManagerBackend->createUrl(ArrayHelper::merge($url, $params));

                if (strpos('backend/web', $result) === false)
                    $result = str_replace('index.php', 'backend/web/index.php', $result);
            }

            $url = $result;
            if (Yii::$app->urlManager->showScriptName != true && $position == BACKEND && REQUIRED_INDEX_PHP == true && !StringHelper::startsWith($url, 'index.php/'))
                $url = 'index.php/' . $url;

            $url = str_replace('//', '/', $url);
            return $url;
        }

        if (empty($url) || $url == '#')
            return '#';

        if (StringHelper::startsWith($url, 'http'))
            return $url;

        if (StringHelper::startsWith($url, 'www'))
            return 'http://' . $url;

        $module = FHtml::currentModule();
        if (empty($module))
            $url = str_replace('{module}/', '', $url);
        else
            $url = str_replace('{module}', FHtml::currentModule(), $url);

        $url = str_replace('{controller}', FHtml::currentController(), $url);
        $url = str_replace('{action}', FHtml::currentAction(), $url);
        $url = str_replace('{domain}', FHtml::currentDomain(), $url);

        if (Yii::$app->urlManager->showScriptName != true && $position == BACKEND && REQUIRED_INDEX_PHP == true && !StringHelper::startsWith($url, 'index.php/'))
            $url = 'index.php/' . $url;

        $url = str_replace('//', '/', $url);

        if (!isset($params))
            $params = [];

        if ($position == BACKEND)
            $result = Yii::$app->urlManager->createUrl(ArrayHelper::merge([$url], $params));
        else {
            $result = Yii::$app->urlManagerBackend->createUrl(ArrayHelper::merge([$url], $params));

            if (strpos('backend/web', $result) === false)
                $result = str_replace('index.php', 'backend/web/index.php', $result);
        }

        return $result;
    }

    public static function loadHtmlFromUrl($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        if (StringHelper::startsWith($url, 'https'))
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($ch);
        curl_close($ch);

        return $html;
    }

    public static function loadJsonFromUrl($url, $array_key = '')
    {
        $result = json_decode(self::loadHtmlFromUrl($url), true);
        if (is_array($result) && !empty($result)) {
            if (ArrayHelper::isIndexed($result))
                return $result[0];
            else if (key_exists($array_key, $result))
                return $result[$array_key];
        }
        return $result;
    }

    public static function crawlLinksInUrl($url)
    {
        $html = self::loadHtmlFromUrl($url);

        $dom = new \DOMDocument();

        @$dom->loadHTML($html);
        $result = [];
        foreach ($dom->getElementsByTagName('a') as $link) {
            $result[] = $link->getAttribute('href');
        }

        return $result;
    }

    public static function format_json($json)
    {
        //return json_encode(json_decode($json), JSON_PRETTY_PRINT);
        if (!is_string($json)) {
            if (phpversion() && phpversion() >= 5.4) {
                return json_encode($json, JSON_PRETTY_PRINT);
            }
            $json = json_encode($json);
        }
        $result = '';
        $pos = 0;               // indentation level
        $strLen = strlen($json);
        $indentStr = "\t";
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;
        for ($i = 0; $i < $strLen; $i++) {
            // Speedup: copy blocks of input which don't matter re string detection and formatting.
            $copyLen = strcspn($json, $outOfQuotes ? " \t\r\n\",:[{}]" : "\\\"", $i);
            if ($copyLen >= 1) {
                $copyStr = substr($json, $i, $copyLen);
                // Also reset the tracker for escapes: we won't be hitting any right now
                // and the next round is the first time an 'escape' character can be seen again at the input.
                $prevChar = '';
                $result .= $copyStr;
                $i += $copyLen - 1;      // correct for the for(;;) loop
                continue;
            }

            // Grab the next character in the string
            $char = substr($json, $i, 1);

            // Are we inside a quoted string encountering an escape sequence?
            if (!$outOfQuotes && $prevChar === '\\') {
                // Add the escaped character to the result string and ignore it for the string enter/exit detection:
                $result .= $char;
                $prevChar = '';
                continue;
            }
            // Are we entering/exiting a quoted string?
            if ($char === '"' && $prevChar !== '\\') {
                $outOfQuotes = !$outOfQuotes;
            }
            // If this character is the end of an element,
            // output a new line and indent the next line
            else if ($outOfQuotes && ($char === '}' || $char === ']')) {
                $result .= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            } // eat all non-essential whitespace in the input as we do our own here and it would only mess up our process
            else if ($outOfQuotes && false !== strpos(" \t\r\n", $char)) {
                continue;
            }
            // Add the character to the result string
            $result .= $char;
            // always add a space after a field colon:
            if ($outOfQuotes && $char === ':') {
                $result .= ' ';
            }
            // If the last character was the beginning of an element,
            // output a new line and indent the next line
            else if ($outOfQuotes && ($char === ',' || $char === '{' || $char === '[')) {
                $result .= $newLine;
                if ($char === '{' || $char === '[') {
                    $pos++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            $prevChar = $char;
        }
        return $result;
    }

    public static function showGoogleMap($address, $zoom = '18', $maptype = 'roadmap', $displaytype = 'place', $width = '100%', $height = '450', $api_key = '')
    {
        if (empty($api_key))
            $api_key = FHtml::config(FHtml::SETTINGS_GOOGLE_API_KEY, 'AIzaSyCHpSPTym5KTydcgF5iwSE721IG0E-VNQA', null, 'Google');

        $address = self::getURLFriendlyName($address);

        $result = '<iframe width="' . $width . '" height="' . $height . '" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/' . $displaytype . '?key=' . $api_key . '&q=' . $address . '&zoom=' . $zoom . '&maptype=' . $maptype . '"></iframe>';
        return $result;
    }

    public static function getURLFriendlyName($name)
    {
        $name = str_replace(' ', '+', $name);
        $name = str_replace('&', ' ', $name);

        return $name;
    }

    public static function searchGoogles($keyword)
    {
        $url = 'http://www.google.com/search?q=' . str_replace(' ', '+', $keyword);
        $html = FHtml::getHtmlDom($url);

        $linkObjs = $html->find('h3.r a');
        $result = [];
        foreach ($linkObjs as $linkObj) {
            $title = trim($linkObj->plaintext);
            $link = trim($linkObj->href);

            // if it is not a direct link but url reference found inside it, then extract
            if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
                $link = $matches[1];
            } else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
                continue;
            }

            $result = array_merge($result, [$title => $link]);
        }

        return $result;
    }

    public static function getHtmlDom($url, $use_include_path = false, $context = null, $offset = -1, $maxLen = -1, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT, $defaultSpanText = DEFAULT_SPAN_TEXT)
    {
        if (strpos($url, '<') !== false && strpos($url, '>') !== false) {
            return str_get_html($url);
        }

        if (!StringHelper::startsWith($url, 'http')) {
            if (self::checkURL('http://' . $url))
                $url = 'http://' . $url;
            else if (self::checkURL('https://' . $url)) {
                $url = 'https://' . $url;
            } else {

            }
        }
        return file_get_html($url, $use_include_path, $context, $offset, $maxLen, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);

//        if (self::checkURL($url)) {
//            return file_get_html($url, $use_include_path, $context, $offset, $maxLen, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
//        } else
//            return null;
    }

    //Return all links in URL (using for crawling

    public static function checkURL($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);
        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 404) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public static function searchPlace($keyword)
    {
        if (empty($keyword))
            $q = FHtml::getRequestParam(['q', 'search', 'place']);
        else
            $q = $keyword;

        $url = 'https://www.google.com/search?q=' . str_replace(' ', '+', $q);

        $html = FHtml::getHtmlDom($url);
        $a = $html->find('span._tA');
        $result = [];
        //$result = array_merge($result, ['search url' => $url]);

        $i = 0;
        foreach ($a as $a1) {
            $i = $i + 1;
            $text = trim($a1->plaintext);
            if (strlen($text) > 50 && $i == 1) {
                $result = array_merge($result, ['address' => $text]);
                continue;
            }
            if (StringHelper::startsWith($text, '+')) {
                $result = array_merge($result, ['tel' => $text]);
                continue;
            }
            if (strpos($text, ':') > 0) {
                $result = array_merge($result, ['hours' => $text]);
                continue;
            }
        }
        $a = $html->find('div._IGf a.fl');
        foreach ($a as $a1) {
            $i = $i + 1;
            $text = trim($a1->href);
            if (StringHelper::startsWith($text, '/url?q=')) {
                $result = array_merge($result, ['website' => substr($text, 7, strpos($text, '/', strpos($text, '://') + 3) - 7)]);
                continue;
            }
        }
        return $result;
    }

    public static function showModelPrice($model, $color = 'red', $show_friendly = true, $show_price = true)
    {
        if (!isset($model) || $show_price === false)
            return '';
        $price = FHtml::getFieldValue($model, ['price', 'cost']);
        $discount = FHtml::getFieldValue($model, ['discount']);
        $old_price = 0;
        $prefix = FHtml::getCurrency($model);

        if (!empty($discount)) {
            if (StringHelper::endsWith($discount, '%'))
                $discount = str_replace('%', '', $discount);

            if (is_numeric($discount)) {
                $old_price = $price;
                $price = $price * (100 - $discount) / 100;
            }
        }

        return self::showPrice($price, $old_price, $discount, $prefix, $color, $show_friendly, $show_price);
    }

    public static function getCurrency($model)
    {
        $result = FHtml::getFieldValue($model, ['currency', 'product_currency']);
        if (empty($result))
            return self::getCurrentCurrency();
    }

    public static function getCurrentCurrency()
    {
        return $main_color = FHtml::config(FHtml::SETTINGS_CURRENCY, 'USD', null, 'Format', FHtml::EDITOR_SELECT, 'currency');
    }

    public static function showPrice($value, $old_value = '', $discount = '', $prefix = '$', $color = 'red', $show_friendly = true, $show_price = true)
    {
        if (empty($value) || $show_price === false)
            return '';

        $result = '<span class="title-price text-' . $color . '">' . self::showCurrency($value, $prefix) . '</span>';

        if (!empty($old_value) && !empty($show_friendly))
            $result .= '<span class = "title-price line-through text-default">' . self::showCurrency($old_value, $prefix) . '</span>';

        if (!empty($discount) && !empty($show_friendly)) {
            if (is_numeric($discount))
                $discount = '-' . $discount . '%';
            $result .= '<small class = "shop-bg-red">' . $discount . '</small>';
        }
        return $result;
    }

    public static function showCurrency($value, $prefix = '')
    {
        if (!isset($value) || empty($value) || !is_numeric($value))
            return '';

        if (empty($prefix))
            $prefix = FHtml::settingCurrency();

        if (is_numeric($value) || (is_string($value) && !empty($value)))
            return number_format($value, FHtml::settingDigitsAfterDecimalFormat(), Fhtml::settingDecimalSeparatorFormat(), Fhtml::settingThousandSeparatorFormat()) . " <small style='font-size:80%; color:grey'>" . $prefix . '</small> ';
        else
            return '';
    }

    public static function showCurrencyInWords($value, $prefix = '')
    {
        $value = round($value);
        if (!isset($value) || empty($value) || !is_numeric($value))
            return '';

        if (empty($prefix))
            $prefix = FHtml::settingCurrency();

        $prefix = FHtml::t('common', self::getCurrencyCode($prefix));

        if (is_numeric($value) || (is_string($value) && !empty($value)))
            return self::showNumberInWords($value) . " " . $prefix . '';
        else
            return '';
    }

    public static function showNumberInWords($number, $prefix = '') {
        $hyphen      = ' ';
        $conjunction = '  ';
        $separator   = ' ';
        $negative    = FHtml::t('common', 'negative') . ' ';
        $decimal     = FHtml::t('common', 'comma'). ' ';
        $dictionary  = array(
            0                   => FHtml::t('common', 'Zero'),
            '01' => FHtml::t('common', 'First'),
            '02' => FHtml::t('common', 'Second'),
            1                   => FHtml::t('common', 'One'),
            2                   => FHtml::t('common', 'Two'),
            3                   => FHtml::t('common', 'Three'),
            4                   => FHtml::t('common', 'Four'),
            5                   => FHtml::t('common', 'Five'),
            6                   => FHtml::t('common', 'Six'),
            7                   => FHtml::t('common', 'Seven'),
            8                   => FHtml::t('common', 'Eight'),
            9                   => FHtml::t('common', 'Nine'),
            10                  => FHtml::t('common', 'Ten'),
            11                  => FHtml::t('common', 'Eleven'),
            12                  => FHtml::t('common', 'Twelve'),
            13                  => FHtml::t('common', 'Thirteen'),
            14                  => FHtml::t('common', 'Fourteen'),
            15                  => FHtml::t('common', 'Fifteen'),
            16                  => FHtml::t('common', 'Sixteen'),
            17                  => FHtml::t('common', 'Seventeen'),
            18                  => FHtml::t('common', 'Eighteen'),
            19                  => FHtml::t('common', 'Nineteen '),
            20                  => FHtml::t('common', 'Twenty'),
            30                  => FHtml::t('common', 'Thirty'),
            40                  => FHtml::t('common', 'Fourty'),
            50                  => FHtml::t('common', 'Fifty'),
            60                  => FHtml::t('common', 'Sixty'),
            70                  => FHtml::t('common', 'Seventy'),
            80                  => FHtml::t('common', 'Eighty'),
            90                  => FHtml::t('common', 'Ninety'),
            100                 => FHtml::t('common', 'hundred'),
            1000                => FHtml::t('common', 'thousand'),
            1000000             => FHtml::t('common', 'million'),
            1000000000          => FHtml::t('common', 'billion'),
            1000000000000       => FHtml::t('common', 'million billion'),
            1000000000000000    => FHtml::t('common', 'trillion'),
            1000000000000000000 => FHtml::t('common', 'billion billion'),
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
// overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . self::showNumberInWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::showNumberInWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::showNumberInWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::showNumberInWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string . $prefix;
    }

    public static function getCurrencyCode($currency, $array = ['USD' => '$', 'VND' => 'đ']) {
        if (key_exists($currency, $array))
            return $array[$currency];
        return $currency;
    }

    public static function showModelRates($model, $max = 5, $color = 'yellow', $show_rate = true, $postfix = 'star', $pull_right = false)
    {
        $value = FHtml::getFieldValue($model, ['rate']);

        return self::showRates($value, $max, $color, $show_rate, $postfix, $pull_right);
    }

    public static function showRates($value, $max = 5, $color = 'yellow', $show_rate = true, $postfix = 'star', $pull_right = false)
    {
        if (!isset($value) || $show_rate === false)
            return '';

        if (!is_numeric($value))
            return $value;
        else if ($value < 10) {
            if ($pull_right === true)
                $result = '<div class="star-vote pull-right">';
            else
                $result = '<div class="star-vote">';

            $result .= '<ul class="list-inline">';
            for ($i = 1; $i <= $max; $i = $i + 1) {
                if ($i <= $value)
                    $result .= '<li><i class="color-' . $color . ' fa fa-' . $postfix . '"></i></li>';
                else if ($i > $value)
                    $result .= '<li><i class="color-' . $color . ' fa fa-' . $postfix . '-o"></i></li>';
            }

            $result .= '</ul></div>';

            return $result;

        }
        return number_format($value, 0, ".", ",") . $postfix;
    }

    public static function showQuote($value)
    {
        if (!empty($value))
            return "<blockquote class='hero'><p><em>$value</em></p></blockquote>";
        else
            return "";
    }

    public static function showAlert($message = '', $type = '', $isGrowl = false, $delay = 3000)
    {
        if (empty($message)) {
            echo AlertBlock::widget([
                'useSessionFlash' => empty($message) ? true : false,
                'delay' => $delay,
                'type' => $isGrowl == true ? AlertBlock::TYPE_GROWL : AlertBlock::TYPE_ALERT
            ]);
        } else {
            if ($type == Alert::TYPE_DANGER)
                $delay = null;
            echo Alert::widget([
                'delay' => $delay,
                'body' => $message,
                'titleOptions' => ['icon' => 'info-sign'],
                'title' => '',
                'type' => isset($type) ? $type : Alert::TYPE_SUCCESS
            ]);
        }
    }

    //HungHX: 20160801

    public static function getHtmlContent($url)
    {
        $html = self::getHtmlDom($url);
        if (!isset($html))
            return $html->outertext;
        else
            return '';
    }

    public static function getHtmlLinks($url)
    {
        $html = self::getHtmlDom($url);
        $results = [];

        foreach ($html->find('a') as $element)
            $results[] = $element->href;

        return $results;
    }

    public static function getHtmlImages($url)
    {
        $html = self::getHtmlDom($url);
        $results = [];

        foreach ($html->find('img') as $element)
            $results[] = $element->src;

        return $results;
    }

    public static function getHtmlElements($url, $search, $plaintext = true)
    {
        $html = self::getHtmlDom($url);
        $results = [];

        foreach ($html->find($search) as $element) {
            $results[] = $plaintext ? $element->plaintext : $element->innertext;
        }

        return $results;
    }

    public static function showNotification($title = '', $message = '', $icon = 'glyphicon glyphicon-info-sign', $type = Growl::TYPE_INFO, $isShowOnTop = false, $isShowOnRight = true)
    {
        echo Growl::widget([
            'type' => $type,
            'title' => $title,
            'icon' => $icon,
            'body' => $message,
            'showSeparator' => true,
            'delay' => 1500,
            'pluginOptions' => [
                'showProgressbar' => true,
                'placement' => [
                    'from' => $isShowOnTop == true ? 'top' : 'bottom',
                    'align' => $isShowOnRight == true ? 'right' : 'left',
                ]
            ]
        ]);
    }

    public static function getImageFolder($model)
    {
        if (is_string($model))
            return str_replace('_', '-', $model);

        return str_replace('_', '-', self::getTableName($model));
    }

    public static function showModel($model, $nameField = ['name', 'title', 'username'], $descriptionField = ['description', 'overview', 'status', 'type', 'category_id'], $layout = self::LAYOUT_NO_LABEL, $css = 'text-default small', $inlineEdit = true)
    {
        $canEdit = FHtml::isInRole($model, FHtml::ACTION_EDIT);
        $result = !empty($nameField) ? '<div style="margin-bottom:5px; font-weight:bold">' . FHtml::showModelField($model, $nameField, '', $layout, $css, '', '', '', '', $inlineEdit) . '</div>' : '';
        if (!empty($descriptionField))
            $result = $result . FHtml::showModelField($model, $descriptionField, '', $layout, $css, '', '', '', '', $inlineEdit);

        return $result;
    }

    public static function showModelPreview($model, $nameField = FHtml::FIELDS_NAME, $descriptionField = FHtml::FIELDS_OVERVIEW, $imageField = ['image', 'avatar'], $statusField = FHtml::FIELDS_STATUS, $layout = self::LAYOUT_TABLE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        $canEdit = FHtml::isInRole($model, FHtml::ACTION_EDIT);
        $image = '';
        foreach ($imageField as $fieldImg) {
            $img = FHtml::getFieldValue($model, $fieldImg);
            if (!empty($img))
                $image .= FHtml::showImage($img, FHtml::getImageFolder($model), '100%', 0, '', $fieldImg, false);
        }
        $result = '<div style="margin-bottom:10px;">' . $image . '</div>';
        $result .= !empty($nameField) ? '<div style="margin-bottom:10px; font-size:150%">' . FHtml::showModelField($model, $nameField, '', self::LAYOUT_NO_LABEL, $css) . '</div>' : '';
        if (!empty($descriptionField))
            $result = $result . '<div style="margin-bottom:10px; color: darkgrey">' . FHtml::showModelField($model, $descriptionField, '', self::LAYOUT_NO_LABEL, $css) . '</div>';
        if (!empty($statusField))
            $result = $result . FHtml::showModelField($model, $statusField, '', $layout, $css);
        return $result;
    }

    public static function showModelPreviewTop($model, $nameField = FHtml::FIELDS_NAME, $descriptionField = FHtml::FIELDS_OVERVIEW, $imageField = FHtml::FIELDS_IMAGES, $statusField = FHtml::FIELDS_STATUS, $layout = self::LAYOUT_TABLE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        $canEdit = FHtml::isInRole($model, FHtml::ACTION_EDIT);
        $image = '';
        $name = '';
        $description = '';
        $status = '';
        foreach ($imageField as $fieldImg) {
            $img = FHtml::getFieldValue($model, $fieldImg);
            if (!empty($img))
                $image .= FHtml::showImage($img, FHtml::getImageFolder($model), '100%', 0, '', $fieldImg, false);
        }
        if (!empty($image))
            $image = '<div class=\'col-xs-2\'><div style="margin-bottom:10px;">' . $image . '</div></div>';
        $name = !empty($nameField) ? '<div style="margin-bottom:10px; font-size:150%">' . FHtml::showModelField($model, $nameField, '', self::LAYOUT_NO_LABEL, $css) . '</div>' : '';
        if (!empty($descriptionField))
            $description = '<div style="margin-bottom:10px; color: darkgrey">' . FHtml::showModelField($model, $descriptionField, '', self::LAYOUT_NO_LABEL, $css) . '</div>';
        if (!empty($statusField))
            $status = FHtml::showModelField($model, $statusField, '', $layout, $css);

        $result = "<div class='row'>$image<div class='col-xs-7'>$name $description</div> <div class='col-xs-3'>$status</div></div>";
        return $result;
    }

    public static function showModelHistory($model, $fields = FHtml::FIELDS_HISTORY, $layout = self::LAYOUT_ONELINE, $css = '')
    {
        $result = FHtml::showModelField($model, $fields, '', $layout, $css);
        return $result;
    }

    public static function showModelHistory2($model, $fields = FHtml::FIELDS_HISTORY, $layout = self::LAYOUT_ONELINE, $css = '')
    {
        $created = FHtml::showUser(FHtml::getFieldValue($model, ['created_user', 'created_by', 'created'])) . ' at ' . FHtml::showDate(FHtml::getFieldValue($model, ['created_date', 'created_at', 'created']));
        $modified = FHtml::showUser(FHtml::getFieldValue($model, ['modified_user', 'modified_by', 'modified', 'updated_user', 'updated_by', 'updated'])) . ' at ' . FHtml::showDate(FHtml::getFieldValue($model, ['modified_date', 'modified_at', 'modified', 'updated_date', 'updated_at', 'updated']));

        $result = !empty($created) ? FHtml::t('common', 'Created by ') . $created . '. ' : '';
        $result .= !empty($modified) ? FHtml::t('common', 'Modified by ') . $modified : '';
        return "<div style='font-size:80%;color:darkgrey; padding-top:20px'>$result</div>";
    }

    //2017/3/6
    public static function showModelFieldValue($model, $field, $showType = '', $dbType = '', $inlineEdit = true)
    {
        if ($showType == 'readonly') {
            $inlineEdit = false;
            $showType = '';
        }

        $result = '';

        if (self::field_exists($model, $field) || method_exists($model, 'get' . ucfirst($field))) {


            if (empty($showType)) {
                $showType = self::getShowType($model, $field);
            }

            $table = FHtml::getTableName($model);
            $value = FHtml::getFieldValue($model, $field);

            $result .= self::showFieldValue($value, FHtml::getFieldValue($model, ['id']), $showType, $table, $field, $dbType, $inlineEdit);
        }
        return $result;
    }

    //2017/3/6
    public static function showModelField($model, $field, $showType = '', $layout = self::LAYOUT_NO_LABEL, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '', $inlineEdit = true)
    {
        if ($showType == 'readonly') {
            $inlineEdit = false;
            $showType = '';
        }

        if ($field == 'id')
            return $model[$field];

        $result = '';
        $arr = [];

        if (is_array($field)) {
            $arr = $field;
        } else
            $arr[] = $field;

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '<table class="table table-bordered" style="width:100%">';

        if (empty($table))
            $table = self::getTableName($model);

        foreach ($arr as $field1) {
            try {

                $arr1 = FHtml::parseAttribute($field1);
                $field1 = $arr1['attribute'];
                if (self::field_exists($model, $field1)) {
                    if (!empty($arr1['format']) && $arr1['format'] != 'raw')
                        $showType1 = $arr1['format'];
                    else if (empty($showType)) {
                        $showType1 = self::getShowType($model, $field1);
                    } else {
                        $showType1 = $showType;
                    }
                    if (!FHtml::isInArray($field1, FHtml::getFIELDS_GROUP(), $table))
                        $newline = '<br/>';
                    else
                        $newline = '&nbsp;';
                    if (!empty($arr1['label']))
                        $label = $arr1['label'];
                    else
                        $label = FHtml::getFieldLabel($model, $field1);

                    $result .= self::showField($label, FHtml::getFieldValue($model, $field1), $showType1, $layout, $css, $table, $field1, $dbType, FHtml::getFieldValue($model, ['id']), '...', $newline, $inlineEdit);
                }
            } catch (Exception $e) {

            }
        }

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '</table>';
        return $result;
    }

    public static function getShowType($model, $field1)
    {
        $showType1 = '';
        if (StringHelper::startsWith($field1, 'is_')) {
            $showType1 = FHtml::SHOW_ACTIVE;
        } else if (FHtml::isInArray($field1, FHtml::FIELDS_DATE, $model)) {
            $showType1 = FHtml::SHOW_DATE;
        } else if (FHtml::isInArray($field1, ['category_id', 'categoryid'])) {
            $showType1 = FHtml::SHOW_LABEL;
        } else if (FHtml::isInArray($field1, FHtml::getFIELDS_GROUP(), $model)) {
            $showType1 = FHtml::SHOW_LABEL;
        } else if (FHtml::isInArray($field1, FHtml::FIELDS_IMAGES, $model)) {
            $showType1 = FHtml::SHOW_IMAGE;
        } else if (StringHelper::endsWith($field1, '_id')) {
            $showType1 = FHtml::SHOW_LOOKUP;
        } else if (StringHelper::endsWith($field1, 'color')) {
            $showType1 = FHtml::SHOW_COLOR;
        } else if (FHtml::isInArray($field1, ['*user', '*user_id'])) {
            $showType1 = FHtml::SHOW_USER;
        } else if (FHtml::isInArray($field1, FHtml::FIELDS_PRICE, $model)) {
            $showType1 = FHtml::SHOW_CURRENCY;
        } else {
            $showType1 = '';
        }
        return $showType1;
    }

    //2017/3/6
    //2017/4/25
    public static function showFieldValue($value, $model_id = '', $showType = '', $table = '', $column = '', $dbType = '', $inlineEdit = true)
    {
        $result = FHtml::showContent($value, $showType, $table, $column, $dbType);
        if (FHtml::isAuthorized(FHtml::ACTION_EDIT, $table, $column) && !FHtml::isInArray($showType, ['readonly', 'view']) && $inlineEdit == true) { // has permission to edit
            if (empty($result))
                $result = FHtml::NULL_VALUE;

            if (FHtml::isInArray($column, FHtml::FIELDS_BOOLEAN, $table)) {
                $result = FHtml::showBooleanEditable($result, $value, $column, $model_id, $table); // Make Field Editable
                FHtml::registerEditorJS($column, FHtml::EDIT_TYPE_INLINE);
            } else {

                $result = FHtml::showContentEditable($result, $value, $column, $model_id, $table, $showType); // Make Field Editable
                FHtml::registerEditorJS($column, FHtml::EDIT_TYPE_INLINE);
            }
        }

        return $result;
    }

    //2017/3/6
    public static function showField($label, $value, $showType = '', $layout = self::LAYOUT_NEWLINE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $model_id = '', $empty_value = '...', $newline = '<br/>', $inlineEdit = true)
    {
        $str = '';
        if ($layout == self::LAYOUT_NEWLINE) {
            if (strlen($label) > 0) {
                $str .= '<label class="[css]">[label]</label><br/>[value]';
            } else {
                $str .= '[value]';
            }
            $newline = !empty($newline) ? $newline : '<br/><br/>';
        } else if ($layout == self::LAYOUT_ONELINE) {
            if (strlen($label) > 0) {
                $str .= '<div class="row"><div class="col-md-4"><label class="[css]">[label]</label></div><div class="col-md-8">[value]</div></div>';
            } else {
                $str .= '[value]';
            }
            $newline = !empty($newline) ? $newline : '<br/>';
        } else if ($layout == self::LAYOUT_ONELINE_RIGHT) {
            if (strlen($label) > 0) {
                $str .= '<div class="row"><div class="col-md-12"><label class="[css]">[label]</label><span class="pull-right">[value]</span></div></div>';
            } else {
                $str .= '[value]';
            }
            $newline = !empty($newline) ? $newline : '<br/>';
        } else if ($layout == self::LAYOUT_TEXT) {
            if (strlen($label) > 0) {
                $str .= '<label class="[css]">[label]:&nbsp;&nbsp; </label>[value]';
            } else {
                $str .= '[value]';
            }
            $newline = !empty($newline) ? $newline : '<br/><br/>';
        } else if ($layout == self::LAYOUT_NO_LABEL) {
            $str .= '[value]';
            $newline = !empty($newline) ? $newline : '<br/><br/>';
        } else if ($layout == self::LAYOUT_TABLE) {
            $str .= '<tr><td class="col-xs-4 bg-grey-cararra">[label]</td></td><td>[value]</td></tr>';
            $newline = '';
        } else {
            $str = $layout;
            $newline = !empty($newline) ? $newline : '<br/>';
        }

        if ($label == '...')
            $label = '&nbsp;';
        else
            $label = FHtml::t('common', $label);

        $result = self::showFieldValue($value, $model_id, $showType, $table, $column, $dbType, $inlineEdit);

        if (strlen($result) == 0)
            $result = $empty_value;

        $a = array($css, $label, $result);
        $b = array('[css]', '[label]', '[value]');
        $str = str_replace($b, $a, $str) . $newline;
        return $str;
    }

    public static function showContent($value, $showType = '', $table = '', $column = '', $dbType = '', $type = '', $empty_value = '', $image_width = '50%', $seperator = ' . ')
    {
        $result = '';

        if ($column == 'id' || FHtml::isInArray($column, FHtml::FIELDS_IMAGES)) {
            $module = FHtml::currentModule();
            $controller = FHtml::currentController();

            $link_url = FHtml::createLink("$module/$controller/view-detail", ['id' => $value], BACKEND, $result, '', '' )   ;
            return $link_url;
        }

        if (is_array($value)) {
            if (ArrayHelper::isIndexed($value)) {
                foreach ($value as $value1) {
                    $result .= self::showContent($value1, $showType, '', '', '', '', '') . $seperator;
                }
            } else {
                foreach ($value as $value1 => $showType1) {
                    $result .= self::showContent($value1, $showType1, '', '', '', '', '') . $seperator;
                }
            }
        } else {

            if ($column == 'user_id') {
                $showType = FHtml::SHOW_LOOKUP;
                $table = '@app_user';
            } else if ($column == 'user') {
                $showType = FHtml::SHOW_LOOKUP;
                $table = '@user';
            }

            if (is_numeric($value) && empty($showType))
                $showType = FHtml::SHOW_NUMBER;

            if ($showType == FHtml::SHOW_LABEL)
                $result = self::showLabel($table . '\\' . $column, $table, $column, $value);
            else if ($showType == FHtml::SHOW_COLOR)
                $result = self::showColor($value);
            else if ($showType == FHtml::SHOW_HIDDEN)
                return '';
            else if ($showType == FHtml::SHOW_NUMBER)
                $result = self::showNumber($value);
            else if ($showType == FHtml::SHOW_CURRENCY)
                $result = self::showCurrency($value);
            else if ($showType == FHtml::SHOW_RATE)
                $result = self::showRates($value);
            else if ($showType == FHtml::SHOW_BOOLEAN) {
                $result = self::showBoolean($value);
            } else if ($showType == FHtml::SHOW_ACTIVE) {
                $result = self::showActive($value, empty($column) ? 'Active' : str_replace('is_', '', $column));
            } else if ($showType == FHtml::SHOW_PARENT) {
                $result = self::showParent($value, $table);
            } else if ($showType == FHtml::SHOW_LOOKUP) {
                $result = self::showLookup($value, $table);
            } else if ($showType == FHtml::SHOW_HTML) {
                $result = self::showHtml($value, $table);
            } else if ($showType == FHtml::SHOW_USER) {
                $result = self::showUser($value);
            } else if ($showType == FHtml::SHOW_ROLE) {
                $result = self::showRole($value);
            } else if ($showType == FHtml::SHOW_DATE)
                $result = self::showDate($value);
            else if ($showType == FHtml::SHOW_TIME)
                $result = self::showTime($value);
            else if ($showType == FHtml::SHOW_IMAGE) {
                if (empty($image_width))
                    $result = FHtml::showImageThumbnail($value, FHtml::config(FHtml::SETTINGS_THUMBNAIL_SIZE, 50), str_replace('_', '-', $table));
                else
                    $result = self::showImage($value, str_replace('_', '-', $table), $image_width, 0);
            } else {
                if (!is_null($value))
                    $result = str_replace("\n", '<br/>', $value);
                else
                    $result = '';
            }

            if ((!isset($result) || $result == '') && $empty_value != '')
                $result = '<small class="text-default">' . $empty_value . '</small>';
        }


        return $result;
    }

    //2017/3/30
    public static function getColorFromArray($key, $table, $column, $value)
    {
        $color = self::getColorFromLabel($value);
        if (!empty($color))
            return $color;

        if (is_array($key))
            $array = $key;
        else
            $array = self::getComboArray($key, $table, $column);

        $i = 0;

        foreach ($array as $id => $name) {
            if ($id == $value)
                break;
            $i = $i + 1;
        }

        return $i < count(FHtml::COLORS) ? FHtml::COLORS[$i] : 'default';
    }

    public static function showObjectPreview($model, $id_field = 'id', $object_type_field = '') {
        if (empty($object_type_field)) {
        } else {
            $object_id = FHtml::getFieldValue($model, $id_field);
            $object_type = FHtml::getFieldValue($model, $object_type_field);
            $model = FHtml::getModel($object_type, '', $object_id);
        }
        $name = FHtml::getFieldValue($model, ['name', 'title', 'username']);
        $description = FHtml::getFieldValue($model, ['description', 'overview']);
        $image = FHtml::getFieldValue($model, ['image', 'thumbnail', 'avatar']);
        $image = FHtml::showImage($image, FHtml::getImageFolder($model), '60px', '');
        $status = FHtml::getFieldValue($model, ['status']); $status = !empty($status) ? FHtml::t('common', 'Status') . ': ' . $status : '';
        $type = FHtml::getFieldValue($model, ['type']); $type = !empty($type) ? FHtml::t('common', 'Type') . ': ' . $type : '';

        return "<div class='col-md-12'><div class='col-md-1'>$image</div><div class='col-md-11'><b>$name</b> <br/>$description.<br/> <small>$status</small></div></div>";
    }

    //2017/3/30
    public static function showLabel($key, $table, $column, $value, $color = 'default', $is_background = true)
    {
        if ($color == 'none' || $color == false) {
            $is_background = false;
            $color = false;
        }

        //sometime it pass 'Table.column' as $table, so we need to remove .column part
        if (strpos($table, '.') > 0)
            $table = reset(explode('.', $table));
        if (strpos($table, '@') > 0)
            $table = str_replace('@', '', $table);

        $text = '';
        $html = '';
        if (!isset($value) || strlen($value) == 0) {
            $text = FHtml::t(self::NULL_VALUE);
            $html = self::showColor('default', $text, false);
        } else {

            $arr = FHtml::decode($value, true);
            if (is_array($arr)) {
                foreach ($arr as $value) {
                    if (empty($value))
                        continue;

                    $arr = FHtml::getArray($key, $table, $column);

                    $arr = self::getKeyValueArray($arr);

                    if (!empty($arr))
                        $arr = ArrayHelper::map($arr, 'id', 'name');
                    if (!empty($arr) && isset($arr[$value])) {
                        $text = FHtml::t('common', $arr[$value]);
                        $color = $color === false ? $color : self::getColorFromArray($arr, $table, $column, $value);
                    } else {
                        if ($column == 'category_id') {
                            $value = str_replace(',', '', $value);
                            $metaItem = FHtml::getModel(FHtml::TABLE_CATEGORIES, '', $value, null, false);
                            if (isset($metaItem)) {
                                $color = $color === false ? $color : "primary";
                                $text = FHtml::t('common', $metaItem->name);
                            } else {
                                $color = $color === false ? $color : "default";
                                $text = "";
                            }
                            //$text = $value;

                        } else {
                            $metaItem = FHtml::getModel(FHtml::TABLE_OBJECT_SETTING, '', ['object_type' => $table, 'meta_key' => $column, 'key' => $value], null, false);
                            if (isset($metaItem)) {
                                $color = $color === false ? $color :$metaItem->color;
                                $text = FHtml::t('common', $metaItem->value);
                            } else {
                                $color = $color === false ? $color : self::getColorFromArray($key, $table, $column, $value);
                                $text = FHtml::t('common', $value);
                            }
                        }
                    }

                    //2017/3/30
                    if (FHtml::isInArray($column, FHtml::FIELDS_GROUP) && !empty($color))
                        $html .= self::showColor($color, $text, $is_background) . '';
                    else
                        $html .= $text;
                }
            }
        }

        return $html;
    }

    //2017/3/30
    public static function showColor($color, $label = '', $is_background = true)
    {
        if (empty($color)) {
            $is_background = false;
        }

        if ($label == '') {
            $label = $color;
            $color = 'default';
        }

        $color = strtolower($color);

        if (strlen($color) > 0) {
            if ($is_background) {
                if (strpos($color, '#') !== false || !in_array($color, ['default', 'primary', 'success', 'warning', 'alert', 'danger']))
                    return "<span class='badge' style='background-color: {$color}'> $label </span> ";
                else
                    return "<span class='badge badge-{$color}'>  $label </span> ";
            } else {
                if (empty($color))
                    return $label;
                else if (strpos($color, '#') !== false || !in_array($color, ['default', 'primary', 'success', 'warning', 'alert']))
                    return "<span class='' style='color: {$color}'> $label </span> ";
                else
                    return "<span class='text-{$color}'>  $label </span> ";
            }
        } else
            return "";
    }

    public static function showHtml($content, $display_type = '') {
        if (empty($content))
            return '';

        if (in_array($display_type, ['pre', 'script', 'style', 'p', 'div', 'span', 'section'])) {
            $content = "<$display_type>\n$content\n</$display_type>";
        }

        return Html::decode($content);
    }

    public static function getColorFromLabel($value)
    {
        $array = self::LABEL_COLORS;
        foreach ($array as $color => $arr) {
            if (FHtml::isInArray($value, $arr))
                return $color;
        }

        return '';
    }

    public static function showNumber($value)
    {
        if (!isset($value) || empty($value) || !is_numeric($value))
            return '';

        if (is_string($value) || is_numeric($value)) {
            $arr = self::number_breakdown($value);

            $decimals = $arr[1] > 0 ? strlen($arr[1]) - 2 : 0;
            if ($decimals > 2)
                $decimals = 2;
            return number_format($value, $decimals, FHtml::settingDecimalSeparatorFormat(), FHtml::settingThousandSeparatorFormat());
        }
        else
            return $value;
    }

    public static function number_breakdown($number, $returnUnsigned = false)
    {
        $negative = 1;
        if ($number < 0)
        {
            $negative = -1;
            $number *= -1;
        }

        if ($returnUnsigned){
            return array(
                floor($number),
                ($number - floor($number))
            );
        }

        return array(
            floor($number) * $negative,
            ($number - floor($number)) * $negative
        );
    }

    public static function showBoolean($value)
    {
        return $value;
    }

    public static function showActive($value, $label = 'Active')
    {
        $html = '';
        $label = strtolower($label);

        $color = FHtml::getColorFromLabel($label);
        if (empty($color))
            $color = 'success';

        if ($value == 1 || $value == true || $value == TRUE)
            $html = '<span class="label label-sm label-' . $color . '">' . FHtml::t('common', $label) . '</span>';
        else
            $html = '<span class="label label-sm label-default">' . FHtml::t('common', $label) . '</span>';
        return $html;
    }

    //HungHX: 20160801
    public static function showParent($value, $table, $keyField = 'id', $displayField = 'name')
    {
        return self::showLookup($value, $table, $keyField, $displayField);
    }

    //HungHX: 20160801
    public static function showLookup($value, $table, $keyField = 'id', $displayField = 'name', $link_url = '{module}/view')
    {
        $table = str_replace('@', '', $table);

        if ($table == 'user' || $table == 'app_user') {
            $keyField = 'id';
            $displayField = 'username';
        }

        if (!isset($value))
            return '';

        $sql_select = '*';
        $sql_table = $table;
        $query = new Query();
        $query->select($sql_select)
            ->from($sql_table);
        $query->andWhere([$keyField => $value]);
        $data = $query->one();

        if (isset($data) && FHtml::field_exists($data, $displayField)) {
            $result = FHtml::getFieldValue($data, $displayField);

            if (!empty($link_url) && FHtml::field_exists($data, $keyField)) {
                $module = self::getModelModule($table);
                if (!empty($module))
                    $module = $module . '/' . BaseInflector::camel2id($table);
                else
                    $module = BaseInflector::camel2id($table);

                $link_url = str_replace('{module}', $module, $link_url);
                $link_url = FHtml::createUrl($link_url, [$keyField => FHtml::getFieldValue($data, $keyField)]);
                $result = '<a href="' . $link_url . '" data-pjax=0 target="_blank">' . $result . '</a>';
            }
            return $result;
        } else
            return $value;
    }

    public static function showRole($value, $table = '', $keyField = 'id', $displayField = 'name')
    {
        if (!isset($value))
            return '';

        $array = self::getComboArray('', '', 'role');
        if (isset($array[$value]))
            return $array[$value];
        else
            return $value;
    }


    //2015/3/23
    public static function showDate($date = null, $format = '', $showTime = false)
    {
        $date = trim($date);
        if (empty($format))
            $format = FHtml::settingDateFormat();
        else if ($format == true) {
            $format = FHtml::settingDateFormat();
            $showTime = true;
        }

        if (is_string($date) && strlen($date) > 10) {
            $showTime = true;
        }

        if (strlen($format) <= 10 && $showTime)
            $format = $format . ' g:i A'; //m.d.Y H:ipm

        if (self::is_timestamp($date)) { // TimeStamp format
            if ($date > date('Y') * 10) {
                if (strlen($format) <= 10)
                    $format = $format . ' g:i A';

                return date($format, $date);
            }
            else
                return $date;
//            $f = date_create();
//            $f = date_timestamp_set($f, $date);
//            $date = $f->format('Y-m-d H:i:s');
//            $timestamp = $date;
        } else {
            $timestamp = strtotime($date);
        }

        $f = \DateTime::createFromFormat('Y-m-d', $date);

        $valid = \DateTime::getLastErrors();
        if ($valid['warning_count'] == 0 and $valid['error_count'] == 0) {
            return date($format, $timestamp);
        }

        $f = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        $valid = \DateTime::getLastErrors();
        if ($valid['warning_count'] == 0 and $valid['error_count'] == 0) {
            return date($format, $timestamp);
        }

        return $date;
    }

    //HungHX: 20160801
    public static function showTime($time, $format = 'g:i A')
    {
        if (is_string($time))
            return $time;

        if (strlen($time) != 0) {
            return $time = date($format, strtotime($time));
        } else
            return '';
    }

    public static function showModelList($model, $field, $showType = '', $layout = self::LAYOUT_TABLE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        $result = '';
        $arr = [];

        if (is_array($field)) {
            $arr = $field;
        } else
            $arr[] = $field;

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '<table class="table table-bordered">';

        if (is_array($model))
            $modelList = $model;
        else
            $modelList[] = $model;

        foreach ($modelList as $model) {
            foreach ($arr as $field1) {
                if (isset($model[$field1]))
                    $result .= self::showField($model->attributeLabels()[$field1], $model[$field1], $showType, $layout, $css, $table, $column, $dbType, $model->id);
            }
        }

        if (is_array($field) && count($field) > 1 && $layout == self::LAYOUT_TABLE)
            $result .= '</table>';

        return $result;
    }

    //HungHX: 20160801

    public static function showArray($model, $showType = '', $layout = self::LAYOUT_TABLE, $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        $result = '';
        $arr = [];

        if ($layout == self::LAYOUT_TABLE)
            $result .= '<table class="table table-bordered">';

        if (is_array($model))
            $modelList = $model;
        else
            $modelList[] = $model;

        foreach ($modelList as $field1 => $value1) {
            if (!is_string($value1) && !is_numeric($value1))
                $value1 = '<div class="co-md-12" style="style="white-space: break-word; !important; word-wrap:break-word;"">'
                    . json_encode($value1) . '</div>';
            $result .= self::showField($field1, $value1, $showType, $layout, $css, $table, $column, $dbType, $type);
        }

        if ($layout == self::LAYOUT_TABLE)
            $result .= '</table>';

        return $result;
    }

    public static function getItemsArray($models, $itemTemplate = '{name}', $fields = ['name']) {
        $arr = [];
        if (isset($models) & is_array($models)) {
            foreach ($models as $model) {
                $item = $itemTemplate;
                foreach ($fields as $field) {
                    $item = str_replace('{' . $field . '}', FHtml::getFieldValue($model, [$field], ''), $item);
                }
                $arr[] = $item;
            }
        }

        return $arr;
    }

    public static function showItemsArray($models, $itemTemplate = '{name}', $fields = ['name'], $separator = '; ')   {
        $items = self::getItemsArray($models, $itemTemplate, $fields);
        return implode($separator, $items);
    }

    public static function generateRandomInArray($arrays)
    {
        $i = array_rand($arrays, 1);
        return $arrays[$i];
    }

    //HungHX: 20160801

    public static function showAttribute($label, $value, $showType = '', $css = 'text-default small', $table = '', $column = '', $dbType = '', $type = '')
    {
        return self::showField($label, $value, $showType, $css, $table, $column, $dbType);
    }

    public static function showLinkButton($value, $onclick, $title)
    {
        return Html::a($value,
            '#',
            ['title' => $title, 'onclick' => "'" . $onclick . "'"]);
    }

    public static function getLabel($key, $table, $column, $value)
    {
        $text = '';
        if (!isset($value) || strlen($value) == 0)
            $text = FHtml::t(self::NULL_VALUE);
        else {
            $model = self::getModel($table);
            $arr = isset($model) && method_exists($model, 'getLookupArray') ? $model::getLookupArray($column) : [];
            if (!empty($arr) && isset($arr[$value]))
                return $text = FHtml::t('common', $arr[$value]);

            if ($column == 'category_id') {

                $metaItem = models\ObjectCategory::findOne(['object_type' => $table, 'id' => $value]);
                if (isset($metaItem)) {
                    $text = FHtml::t('common', $metaItem->name);
                } else {
                    $text = "";
                }
            } else {
                $metaItem = ObjectSetting::findOne(['object_type' => $table, 'meta_key' => $column, 'key' => $value]);
                if (isset($metaItem)) {
                    $text = FHtml::t('common', $metaItem->value);
                } else {
                    $text = FHtml::t('common', $value);
                }
            }
        }

        return $text;
    }

    public static function getComboArrayFilter($key, $table, $column, $isCache = true, $id_field = 'id', $name_field = 'name', $hasNull = true, $search_params = [], $limit = 0)
    {
        $data = self::getComboArray($key, $table, $column, $isCache, $id_field, $name_field, $hasNull, $search_params, $limit);
        //var_dump($data);die;
        $result = [];
        foreach ($data as $id => $value)
            $result = ArrayHelper::merge($result, [',' . $id . ',' => $value]);
        return $result;
    }

    public static function getComboArrayNoNull($key, $table, $column, $isCache = true, $id_field = 'id', $name_field = 'name')
    {
        return ArrayHelper::merge(['' => FHtml::t('common', 'Empty')], self::getComboArray($key, $table, $column, $isCache, $id_field, $name_field));
    }

    //Datetime
    public static function Now($format = 'Y-m-d H:i:s', $timezone = SERVER_TIME_ZONE)
    {
        if (!empty($timezone))
            date_default_timezone_set($timezone);
        return date($format);
    }

    public static function time($timezone = SERVER_TIME_ZONE)
    {
        if (!empty($timezone))
            date_default_timezone_set($timezone);
        return time();
    }

    //HungHX: 20160801
    public static function Today($format = 'Y-m-d', $timezone = SERVER_TIME_ZONE)
    {
        if (!empty($timezone))
            date_default_timezone_set($timezone);
        return date($format);
    }

    public static function date($format = 'Y-m-d', $timezone = SERVER_TIME_ZONE)
    {
        if (!empty($timezone))
            date_default_timezone_set($timezone);
        return date($format);
    }

    public static function toArrayFromDbComment($commment, $name = '')
    {
        $array = FHtml::toArray($commment, ';', ':');
        if (isset($array['data'])) {
            $a = FHtml::toArray(str_replace(['[', ']'], ['', ''], $array['data']), ',', '=');
            $arr1 = [];
            foreach ($a as $key => $value) {
                $arr1 = array_merge($arr1, [$key => $value]);
            }
            $array['data'] = $arr1;
        }

        if (!key_exists('group', $array))
            $array['group'] = null;
        if (!key_exists('editor', $array))
            $array['editor'] = null;
        if (!key_exists('related', $array))
            $array['related'] = null;
        if (!key_exists('meta', $array))
            $array['meta'] = null;
        return $array;
    }

    public static function firstOf($text, $char = '_')
    {
        if (strpos($text, $char) !== FALSE)
            return substr($text, 0, strpos($text, $char));
        else
            return '';
    }

    public static function toArray($text, $seprator1 = ';', $splitter1 = '=')
    {
        //echo $text . ':';
        $arr = explode($seprator1, $text);
        $result = [];
        foreach ($arr as $item) {
            $arr1 = explode($splitter1, $item);
            $key = reset($arr1);
            $value = end($arr1); //echo $key . ' ' . $value;
            //echo $key . '=>' . $value . ' ';
            $result = array_merge($result, [$key => $value]);
        }

        return $result;
    }

    public static function Format()
    {
        return \Yii::$app->formatter;
    }

    public static function isVisibleInCreate($module, $field, $form_type = '', $manualValue = null)
    {
        return self::isAuthorized(self::ACTION_VIEW, $module, $field, 'create', $form_type, '', '', $manualValue);
    }

    public static function isEditInCreate($module, $field, $form_type = '', $manualValue = null)
    {

        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'create', $form_type, '', '', $manualValue);
    }

    public static function isVisibleInUpdate($module, $field, $form_type = '', $manualValue = null)
    {

        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'update', $form_type, '', '', $manualValue);
    }

    public static function isEditInUpdate($module, $field, $form_type = '', $manualValue = null)
    {

        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'update', $form_type, '', '', $manualValue);
    }

    public static function isVisibleInView($module, $field, $form_type = '', $manualValue = null)
    {

        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'view', $form_type, '', '', $manualValue);
    }

    public static function buildSerialGridColumn()
    {
        return [
            'class' => 'kartik\grid\SerialColumn',
            'width' => '30px',
        ];
    }

    public static function buildLookupGridColumn($moduleName, $field)
    {
        return [ //name: user_id, dbType: int(11), phpType: integer, size: 11, allowNull:
            'class' => FHtml::getColumnClass($moduleName, $field, ''),
            'visible' => FHtml::isVisibleInGrid($moduleName, $field, ''),
            'format' => 'raw',
            'attribute' => 'user_id',
            'value' => function ($model) {
                return FHtml::showContent($model->user_id, FHtml::SHOW_LABEL, 'truck_driver', 'user_id', 'int(11)', 'truck-driver');
            },
            'hAlign' => 'left',
            'vAlign' => 'middle',
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ''],
            'filter' => FHtml::getComboArray('@app_user', 'truck_driver', $field, true, 'id', 'name'),
            'contentOptions' => ['class' => 'col-md-1 nowrap'],
        ];
    }

    public static function getColumnClass($module, $field, $form_type = '', $manualValue = null)
    {
        if (isset($manualValue))
            return $manualValue;

        if (self::isEditInGrid($module, $field, $form_type))
            return self::COLUMN_EDIT;
        else
            return self::COLUMN_VIEW;
    }

    public static function isEditInGrid($module, $field, $form_type = '', $manualValue = null)
    {
        if (isset($manualValue))
            return $manualValue;

        if (FHtml::isInArray($field, ['id', 'application_id']))
            return false;
        return self::isAuthorized(self::ACTION_EDIT, $module, $field, 'index', $form_type, '', '', $manualValue);
    }

    public static function isVisibleInGrid($module, $field, $form_type = '', $manualValue = null)
    {
        if (isset($manualValue))
            return $manualValue;

        $object_type = BaseInflector::camel2id($module, '_');
        $result = self::isAuthorized(self::ACTION_VIEW, $module, $field, 'index', $form_type, '', '', $manualValue);
        if (!$result)
            return false;

        if (FHtml::isDynamicFormEnabled()) {
            $column = FHtml::getObjectColumn($object_type, $field);
            if (isset($column) && (!FHtml::getFieldValue($column, 'is_column')))
                return false;
        }

        return true;
    }

    public static function getCurrentUserRole()
    {
        return self::getCurrentRole();
    }

    public static function showSummary($modelSearch, $params = [], $type = 'count', $table = '', $column = '', $value = '')
    {
        $sum = self::getSummary($modelSearch, $params, $type, $table, $column);
        $color = self::getColor('', $table, $column, $value);
        $colort = self::showBadge($sum, $color);
        return $colort;
    }

    public static function getSummary($modelSearch, $params = [], $type = 'count', $table = '', $column = '')
    {
        $modelProvider = $modelSearch->search($params);
        if ($type == 'count')
            return $modelProvider->query->count();
        else if ($type == 'sum')
            return $modelProvider->query->sum();
        else
            return $modelProvider->query->count();
    }

    public static function getColor($key, $table, $column, $value)
    {
        $metaItem = ObjectSetting::findOne(['object_type' => $table, 'meta_key' => $column, 'key' => $value]);
        if (isset($metaItem)) {
            return $metaItem->color;
        } else {
            return self::getColorFromArray($key, $table, $column, $value);
        }
    }

    public static function showBadge($value, $color = 'default', $cssClass = 'pull-right')
    {
        return '<span class="badge badge-' . $color . ' ' . $cssClass . '">' . $value . '</span>';
    }

    public static function displayPercentage($value, $defaultValue = '')
    {
        return self::showPercentage($value, $defaultValue);
    }

    public static function showPercentage($value, $defaultValue = '')
    {
        if (isset($value)) {
            return $value . '%';
        } else
            return $defaultValue;
    }

    public static function getFullUploadFolder($model = '', $position = false) {
        $result = Yii::getAlias('@' . UPLOAD_DIR);

        if (is_object($model)) {
            $model = FHtml::getImageFolder($model);
        }

        if (!empty($model))
            $result = "$result/$model/";

        $result = self::getApplicationUploadFolder($result, $position);

        return $result;
    }

    public static function getApplicationUploadFolder($folder, $position = false) {

        $application_id = FHtml::currentApplicationFolder();
        if (!empty($application_id)) {
            $folder = str_replace('backend\\web\\upload', "applications\\$application_id\\upload", $folder);
            $folder = str_replace('backend/web/upload', "applications/$application_id/upload", $folder);
        }

        return $folder;
    }

    public static function getUploadedFiles($model, $fields, $fileName = '', $oldModel = null)
    {
        $files = [];
        if (!isset($fields) || count($fields) == 0)
            return $files;

        foreach ($fields as $field) {
            if (empty($field) || !self::field_exists($model, $field))
                continue;

            $file = FUploadedFile::getInstance($model, $field . '_upload');

            if (!isset($file)) {
                $file = FUploadedFile::getInstance($model, $field);
            }
            if ($file) {
                $fileTitle = FHtml::getFieldValue($model, ['name', 'title', 'username']);
                $fileTitle = strtolower(self::cleanString($fileTitle));

                if ($fileName == '') {
                    $file->name = $fileTitle . '_' . time() . rand(0, 1000) . '.' . $file->extension;
                } else {
                    $file->name = $fileTitle . '_' . str_replace("-", "_", $fileName) . '_' . $field . '.' . $file->extension;
                }
                $file->oldName = $model[$field];
                $file->fieldName = $field;
                $model[$field] = $file->name;
                $files[] = $file;
            } else {
//                if (isset($oldModel) && !key_exists($field . '_upload', $_POST[StringHelper::basename($model::className())])) {
//                    $model[$field] = $oldModel[$field];
//                }
            }
        }

        return $files;
    }

    public static function saveFiles($files, $folder, $model = null, $autoSave = true)
    {
        if (!isset($files))
            return $model;

        if (is_array($files)) {

            foreach ($files as $file) {
                FHtml::saveFile($file, $file->oldName, $folder);
                if (isset($model) && self::field_exists($model, $file->fieldName))
                    $model[$file->fieldName] = $file->name;
            }
        } else {
            $file = $files;
            FHtml::saveFile($file, $file->oldName, $folder);
            if (isset($model) && self::field_exists($model, $file->fieldName))
                $model[$file->fieldName] = $file->name;
        }

        if (isset($model) && $autoSave)
            $model->save();

        return $model;
    }

    public static function saveFile($file, $old_filename, $folder)
    {
        if ($file) {
            if (isset($old_filename) && !empty($old_filename)) {
                self::deleteFile($old_filename, $folder);
            }
            self::createDir($folder);

            $file->saveAs($folder . $file->name);
        }
    }

    public static function deleteFile($old_filename, $folder = '') {
        if (is_file($folder . $old_filename)) {
            unlink($folder . $old_filename);
        }
    }

    public static function createDir($folder = '') {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
    }

    public static function getAttribute($model, $field)
    {
        return self::getFieldValue($model, $field);
    }

    //2017/3/21: Add counter
    public static function buildTabsSmall($key, $css = 'tabs nav-small', $field = 'id', $url = '', $value = '', $table = '', $column = '', $isCache = true, $showCounter = false)
    {
        return self::buildTabs($key, $css, $field, $url, $value, $table, $column, $isCache, $showCounter);
    }

    public static function buildTabs($key, $css = '', $field = 'id', $url = '', $value = '', $table = '', $column = '', $isCache = true, $showCounter = true)
    {
        $table = empty($table) ? $key : $table;
        $column = empty($column) ? $field : $column;
        if (is_array($key)) {
            $items = [];
            foreach ($key as $id => $name) {
                if (ArrayHelper::isIndexed($key))
                    $id = $name;

                if ($name == 'All')
                    $id = '';

                $items[] = ['id' => $id, 'name' => $name];
            }
        } else {
            if (StringHelper::startsWith($field, 'is_')) {
                $items[] = ['id' => 1, 'name' => FHtml::t(BaseInflector::camel2words($field))];
            } else {
                $items = FHtml::getArray($key, $table, $column, $isCache);
                $items = self::getKeyValueArray($items);
            }
        }

        $str = '';

        if (isset($items)) {
            $is_tab = StringHelper::startsWith($css, 'tabs') ? true : false;
            $div_css = $is_tab ? 'pull-left' : '';
            $ul_css = $is_tab ? ('pull-left nav nav-tabs ' . $css) : 'nav';
            $label = $is_tab ?  '' : ('<div style="font-weight:bold"><small style="color:grey">' . FHtml::getFieldLabel($table, $column) . '</small></div>');
            $style = $is_tab ? 'border-bottom:1px solid lightgrey; margin-right:15px': '';

            $str = "<div class='$div_css' style='$style'>$label<ul class='$ul_css' style='margin-left:-15px; margin-bottom:0px !important'>";
            $url = empty($url) ? '{module}/{controller}/{action}' : $url;
            $field = empty($field) && is_string($key) ? $key : $field;
            $value = empty($value) ? FHtml::getRequestParam($field) : $value;
            foreach ($items as $id => $item) {

                $table = empty($table) ? $key : $table;
                if (StringHelper::startsWith($css, 'tabs')) {
                    if ($value == $item['id']) {
                        $li_css = 'active';
                        $url1 = FHtml::createUrl($url, FHtml::mergeRequestParams(FHtml::RequestParams($field)));
                    }
                    else {
                        $li_css = '';
                        $url1 = FHtml::createUrl($url, FHtml::mergeRequestParams(FHtml::RequestParams(), [$field => $item['id']]));
                    }

                    $str .= '<li class="' . $li_css . '"><a href="' . $url1 . '">' . FHtml::showLabelWithCounter($item['id'], $table, $field, $item['name'], $showCounter) . '</a></li>';
                } else {
                    if ($value == $item['id']) {
                        $li_css = StringHelper::startsWith($css, 'tabs') ? 'active' : 'bg-white';
                        $url1 = FHtml::createUrl($url, FHtml::mergeRequestParams(FHtml::RequestParams($field)));
                    }
                    else {
                        $li_css = '';

                        $url1 = FHtml::createUrl($url, FHtml::mergeRequestParams(FHtml::RequestParams(), [$field => $item['id']]));
                    }
                    $str .= '<li class="' . $li_css . '"><a href="' . $url1 . '">' . FHtml::showLabelWithCounter($item['id'], $table, $field, $item['name'], $showCounter) . '</a></li>';
                }
            }
            $str .= '</ul></div>';
        }
        return $str;
    }

    public static function showLabelWithCounter($key, $model, $field, $name, $showCounter = true)
    {
        $count = '';
        if ($showCounter == true) {
            $table = $model;
            $count = FModel::countModels($table, [$field => $key]); //2017/3/21: get Counter
            if ($count != 0)
                $count = '&nbsp;<span style="">' . self::showBadge($count, FHtml::getColor('', $table, $field, $key)) . '</span>';
            else $count = '';
        }

        return FHtml::t('common', $name) . $count;
    }

    public static function buildGridFiltersVertical($table, $field = ['category_id', 'type', 'status', 'is_active', 'is_top', 'is_hot', 'lang'], $seperator = '', $css = 'tabs nav-normal')
    {
        $seperator = false;
        return self::buildGridFilters($table, $field, $seperator, $css);
    }

    public static function buildGridFiltersTab($table, $field = ['category_id', 'type', 'status', 'is_active', 'is_top', 'is_hot', 'lang'], $seperator = '', $css = 'tabs nav-normal')
    {
        $seperator = true;
        return self::buildGridFilters($table, $field, $seperator, $css);
    }

    //2017/3/21: add counter
    public static function buildGridFilters($table, $field = ['category_id', 'type', 'status', 'is_active', 'is_top', 'is_hot', 'lang'], $seperator = '', $css = 'tabs nav-normal')
    {
        $result = '';
        $model = FHtml::createModel($table);
        if (is_string($model))
            return $model;

        if (!isset($model))
            return '';

        if (is_string($field))
            $field = explode(',', $field);

        if ($seperator === 'tab' || $seperator === 'horizontal' || $seperator === true) {
            $seperator = '';
            $css = 'tabs nav-normal';
        }
        else if ($seperator === 'list' || $seperator === 'vertical' || $seperator === false) {
            $seperator = '<br/>';
            $css = 'nav-normal';
        }

        if (is_array($field)) {
            foreach ($field as $fielditem) {
                if (self::field_exists($model, $fielditem))
                    $result = $result . self::buildTabs($table, $css, $fielditem) . $seperator;
            }
        }

        return $result;
    }

    public static function buildAdminToolbar($table, $field = ['category_id', 'type', 'status', 'is_active', 'is_top', 'is_hot', 'lang'], $views = ['_grid', '_list'], $css = 'hidden-print')
    {
        $result = '';
        $field1 = self::buildGridFilters($table, $field);
        $views1 = self::buildViewOptions();

        $result = "<div class='row $css hidden-print'><div class='col-md-10 col-xs-12'>$field1</div><div class='col-md-2 col-xs-12 text-right  no-padding'>$views1</div></div>";
        //return $result;
        return "<div class='col-md-12'>$result</div>";
    }

    public static function buildViewOptions($right_position = true)
    {
        $key = [
            FListView::VIEW_GRID_SMALL => '<span class="glyphicon glyphicon-th"></span>',
            FListView::VIEW_GRID_BIG => '<span class="glyphicon glyphicon-th-large"></span>',
            FListView::VIEW_LIST => '<span class="glyphicon glyphicon-list"></span>',
            FListView::VIEW_IMAGE => '<span class="glyphicon glyphicon-picture"></span>',
            FListView::VIEW_PRINT => '<span class="glyphicon glyphicon-print"></span>',
        ];
        $result = self::buildTabsSmall($key, 'tabs nav-small', 'view');
        if ($right_position)
            $result = '<div class="pull-right">' . $result . '</div>';
        return $result;
    }

    public static function findViewFile($view, $context = null)
    {
        $page = self::currentView();

        if (strncmp($view, '@', 1) === 0) {
            // e.g. "@app/views/main"
            $file = Yii::getAlias($view);
        } elseif (strncmp($view, '//', 2) === 0) {
            // e.g. "//layouts/main"
            $file = Yii::$app->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
        } elseif (strncmp($view, '/', 1) === 0) {
            // e.g. "/site/index"
            if (Yii::$app->controller !== null) {
                $file = Yii::$app->controller->module->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
            } else {
                return '';
            }
        } elseif ($context instanceof ViewContextInterface) {
            $file = $context->getViewPath() . DIRECTORY_SEPARATOR . $view;
        } elseif (($currentViewFile = $page->getViewFile()) !== false) {
            $file = dirname($currentViewFile) . DIRECTORY_SEPARATOR . $view;
        } else {
            return '';
        }

        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
        $path = $file . '.' . $page->defaultExtension;
        if ($page->defaultExtension !== 'php' && !is_file($path)) {
            $path = $file . '.php';
        }

        if (is_file($path))
            return $path;
        else
            return false;
    }

    public static function renderView($view, $params = [], $context = null, $displayError = true, $is_widget = false) {
        if (!isset($context))
            $context = FHtml::currentControllerObject();

        $viewFile = self::findViewFile($view, $context);
        $page = self::currentView();

        if (is_file($viewFile)) {
            if ($is_widget) {
                return BaseWidget::widget(array_merge(['display_type' => $view, 'params' => $params], $params));
            } else {
                return $page->render($view, $params, $context);
            }
        }
        else {
            if ($displayError)
                echo self::showErrorMessage(FHtml::t('common', 'View not found: ') . $view);
            return '';
        }
    }

    public static function render($view, $viewType = '', $params = [], $context = null, $displayError = true, $is_widget = false)
    {
        if (!isset($context))
            $context = FHtml::currentControllerObject();

        if (is_array($viewType) && empty($params)) {
            $params = $viewType;
            $viewType = '';
        }

        $arr = self::getViews($view, $is_widget);
        //try to look into array of Views
        if (is_array($arr)) {
            foreach ($arr as $view1) {
                $result = self::renderView($view1, $params, $context, false, $is_widget);
                if (!empty($result))
                    return $result;
            }
            if ($displayError)
                echo self::showErrorMessage(FHtml::t('common', 'View not found: ') . implode('; ', $arr));
        }

        if (empty($viewType)) {
            return self::renderView($view, $params, $context, true, $is_widget);
        }

        $viewFile = self::findViewFile($view . $viewType, $context);
        if (empty($viewFile)){
            return self::renderView($view . $viewType, $params, $context, true, $is_widget);
        }

        $viewFile = Yii::getAlias($viewFile);

        $page = self::currentView();

        if ($page->theme !== null) {
            $viewFile = $page->theme->applyTo($viewFile);
        }
        if (is_file($viewFile)) {
            return self::renderView($view . $viewType, $params, $context);
        } else {
            return self::renderView($view, $params, $context);
        }
    }

    public static function renderViewWidget($view, $params = []) {
        return self::render($view, '', $params, FHtml::currentView(), true, true);
    }

    public static function getViews($view, $is_widget = false) {
        $application_id = FHtml::currentApplicationFolder();
        $controller = FHtml::currentController();

        $zone = FHtml::currentZone();
        $module = FHtml::currentModule();
        $arr1 = [];

        if (is_array($view) && count($view) > 1) {
            $arr1[] = $view[0];
            $view = $view[1];
        }

        if (is_string($view) && !empty($application_id)) {
            if (!empty($zone))
                $arr1[] =  "@applications/$application_id/$zone/$controller/$view.php";
            if (!empty($module))
                $arr1[] =  "@applications/$application_id/$zone/modules/$module/views/$controller/$view.php";
        }
        if (is_string($view))
            $arr1[] = $view;
        else if (is_array($view))
            $arr1 = array_merge($arr1, $view);

        if ($is_widget) {
            if (!empty($zone))
                $arr1[] =  "@$zone/views/$controller/$view.php";
            if (!empty($module))
                $arr1[] =  "@$zone/modules/$module/views/$controller/$view.php";
        }
        return $arr1;
    }

    public static function findView($view)
    {
        $arr1 = self::getViews($view);

        //try to look into array of Views
        if (is_array($arr1)) {
            foreach ($arr1 as $view1) {
                $result = FHtml::findViewFile($view1);

                if (is_file($result)) {
                    return $view1;
                }
            }
        }

        return $view;
    }

    public static function isInArray($name, $arrays, $table = '', $character = '*')
    {
        if (is_object($table))
            $table = self::getTableName($table);

        foreach ($arrays as $item) {
            if (StringHelper::startsWith($item, $character)) {
                if (StringHelper::endsWith($name, trim($item, $character))) {
                    return true;
                }
                if (!empty($table) && StringHelper::endsWith($table . '.' . $name, trim($item, $character))) {
                    return true;
                }
            } else if (StringHelper::endsWith($item, $character)) {
                if (StringHelper::startsWith($name, trim($item, $character))) {
                    return true;
                }
                if (!empty($table) && StringHelper::startsWith($table . '.' . $name, trim($item, $character))) {
                    return true;
                }
            } else {
                if ($name === $item) {
                    return true;
                }
                if (!empty($table) && ($table . '.' . $name) == $item) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function buildCategoriesMenu($object_type, $linkurl = '', $ul_css = 'list-unstyled simple-list margin-bottom-20', $li_label = '<i class="fa fa-angle-right"></i>    ')
    {
        if (empty($linkurl))
            $linkurl = '/' . strtolower(BaseInflector::id2camel($object_type)) . '/list';

        $result = "<ul class='" . $ul_css . "'>";
        $cats = FHtml::getCategoriesByType($object_type);
        foreach ($cats as $cat) {
            $result .= '<li>' . $li_label . '<a href="' . FHtml::createUrl($linkurl, ['category_id' => $cat->id]) . '">' . $cat->name . '</a></li>';
        }
        $result .= '</ul>';

        return $result;
    }

    //2017/3/14
    public static function showActionsButton($model, $canEdit = true, $canDelete = true, $template = '<div class="row"><div class="col-md-7 col-xs-12">{save}{view} | {cancel} </div> <div class="col-md-5 col xs-12 pull-right"> {delete} {clone}</div></div>')
    {
        $action = '';
        if (Yii::$app->request->isAjax && FHtml::isInArray($action, ['view', 'view-detail']))
            return '';

        $result = $template;
        $saveT = '';
        $deleteT = '';
        $cancelT = '';
        $cloneT = '';
        if (!$model->isNewRecord && $canDelete) {
            $deleteT = ' &nbsp;' . FHtml::a('<i class="fa fa-trash"></i> ' . FHtml::t('common', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger pull-right',
                    'data' => [
                        //'confirm' => FHtml::t('common', 'Are you sure to delete ?'),
                        'method' => 'post',
                    ],
                ]) . '';
        }

        if ($canEdit) {
            $saveT = FHtml::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('common', 'Save & Back'), ['class' => 'btn btn-primary']);

            $saveT .= '&nbsp;' .FHtml::submitButton('<i class="fa fa-save"></i> ' . FHtml::t('common', 'Save'), ['class' => 'btn btn-default', 'onclick' => 'submitForm("save")']);
            $cloneT = '&nbsp; ' . FHtml::submitButton('<i class="fa fa-copy"></i> ' . FHtml::t('common', 'Save & Clone '), ['class' => 'btn btn-success pull-right', 'onclick' => 'submitForm("clone")']);
        }

        $cancelT = FHtml::a('<i class="fa fa-undo"></i> ' . FHtml::t('common', 'Back'), ['index'], ['class' => 'btn btn-default', 'data-pjax' => 0]);
        $viewT = empty($model->id) ? '' : '  ' . FHtml::a('<i class="fa fa-print"></i> ' . FHtml::t('common', 'Preview'), ['view', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 0]);
        $editT = empty($model->id) ? '' : '  ' . FHtml::a('<i class="fa fa-edit"></i> ' . FHtml::t('common', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-warning pull-right', 'data-pjax' => 0]);
        $printT = '  ' . @"<a class=\"btn blue hidden-print \" onclick=\"javascript:window.print();\"> Print <i class=\"fa fa-print\"></i> </a>";

        $result = str_replace('{save}', $saveT, $result);
        $result = str_replace('{clone}', $cloneT, $result);
        $result = str_replace('{delete}', $deleteT, $result);
        $result = str_replace('{view}', $viewT, $result);
        $result = str_replace('{edit}', $editT, $result);
        $result = str_replace('{print}', $printT, $result);

        $result = str_replace('{cancel}', $cancelT, $result);

        $js = @"<input type=\"hidden\" id=\"saveType\" name=\"saveType\"> <script language=\"javascript\" type=\"text/javascript\">
                function submitForm(saveType) {
                    $('#saveType').val(saveType);
                }
            </script>";
        $result .= $js;

        return '<div class="hidden-print" style="border-top:1px dashed lightgrey; padding-top:20px">' . $result . self::showModelHistory2($model) . '</div>';
    }

    public static function showViewButtons($model, $canEdit = true, $canDelete = true, $template = '{print}{edit}{cancel}')
    {
        return self::showActionsButton($model, $canEdit, $canDelete, $template);
    }

    public static function showPrintHeader($title = '')
    {
        $image = FHtml::showCurrentLogo();
        $company = '<b>' . FHtml::settingCompanyName() . '</b><br/>';
        $desc = $company . FHtml::showDate(date('Y-m-d'));
        $result = "<div class='col-md-12 col-xs-12 no-padding' style='padding:20px; color:grey; border-bottom: 1px dashed lightgrey; padding-bottom: 10px;margin-bottom: 20px'><div class='col-xs-2 pull-left' style='padding-top:10px'>{$image}</div><div class='col-xs-7'><h2>{$title}</h2></div><div class='col-xs-3 pull-right text-right' style='padding:10px'><small>{$desc}</small></div></div>";
        return "<div class='row'>$result</div>";
    }

    //build frontend Menu by Categories

    public static function getBaseUrl($view = null)
    {
        if (!isset($view)) {
            return $baseUrl = Yii::$app->request->baseUrl;
        }
        $asset = CustomAsset::register($view);
        return $baseUrl = $asset->baseUrl;
    }

    //2017.5.8
    public static function baseUploadFolder() {
        $zone = FHtml::currentZone();
        $baseUrl = self::getBaseUrl();
        if ($zone == FRONTEND)
            $url = $baseUrl . DS . BACKEND . DS . WEB_DIR . DS . UPLOAD_DIR;
        else
            $url = $baseUrl . '/' . UPLOAD_DIR;
        return $url;
    }

    public static function createLink($url, $params = [], $position = BACKEND, $label = '...', $target = '_blank', $css = 'btn btn-xs btn-default')
    {
        $url = self::createUrl($url, $params, $position);
        return '<a data-pjax=0 href="' . $url . '" target="' . $target . '" class="' . $css . '">' . $label . '</a>';
    }

    public static function createModuleUrl($module, $url, $params = [], $position = BACKEND)
    {
        if (!empty($module))
            $url = '/' . $module . '/' . $url;
        $url = str_replace('//', '/', $url);

        return self::createUrl($url, $params, $position);
    }

    public static function createBackendUrl($object_type, $params = null)
    {
        $module = FHtml::getModelModule($object_type);
        if (!empty($module))
            $module = $module . '/';
        $controller = str_replace('_', '-', $object_type);
        $url = FHtml::createUrl($module . $controller . '/index', $params, FRONTEND);
        return $url;
    }

    public static function createBackendActionUrl($url)
    {
        $params = self::RequestParams(['id']);
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url = array_merge($url, [$key => $value]);
            }
        }
        return Url::to($url);
    }

    public static function makeModelLinkUrl($item, $link_url, $included_params = ['id', 'name', 'category_id'], $params = []) {
        foreach ($included_params as $param) {
            if (strpos($link_url, "{$param}") !== false) {
                $link_url = str_replace("{{$param}}", FHtml::getFieldValue($item, $param), $link_url);
            }
        }
        $linkurl = FHtml::getFieldValue($item, ['linkurl', 'website']);
        $linkurl = empty($linkurl) ? FHtml::createUrl($link_url, $params) : $linkurl;
        return $linkurl;
    }

    public static function showLinkUrl($label, $url, $display_viewmore = false, $params = [], $target = '')
    {
        if (!empty($params))
            $url = self::createUrl($url, $params);

        if (empty($url) || $url == '#' || $display_viewmore)
            return $label;

        $css = '';
        if (empty($label) || ($label == '...')) {
            $label = $display_viewmore ? FHtml::t('common', 'View more') : $url;
            $css = 'btn-u btn-u-sm rgba-' . FHtml::config(FHtml::SETTINGS_MAIN_COLOR, 'green', null, 'Theme', FHtml::EDITOR_SELECT, 'color');
        }

        return '<a class="' . $css . '" target="' . $target . '" href="' . $url . '">' . $label . '</a>';
    }

    public static function showViewMoreUrl($url, $display_viewmore = true, $params = [], $label = '', $color = '', $css = '')
    {
        if (!$display_viewmore)
            return '';

        if (!empty($params))
            $url = self::createUrl($url, $params);
        if (empty($color))
            $color = FHtml::currentApplicationMainColor();
        if (empty($label))
            $label = FHtml::t('common', 'View more');

        $css = empty($css) ? 'btn-u btn-u-sm' : $css;
        $css = $css . ' rgba-' . $color;
        return '<a class="' . $css . '" href="' . $url . '">' . $label . '</a>';
    }

    public static function buildGridColumns($object_type = '', $columnsOld = [], $currentRole = '', $buttonsType = '', $displayType = '', $editType = '')
    {
        $form_type = FHtml::getRequestParam('form_type');
        $moduleKey = $object_type;
        $moduleName = str_replace('-', '_', $moduleKey);

        if (empty($currentRole))
            $currentRole = FHtml::getCurrentUserRole();
        if (empty($buttonsType))
            $buttonsType = FHtml::config(FHtml::SETTINGS_GRID_BUTTONS_TYPE);

        $columnsModel = FHtml::getObjectColumns($moduleName);

        if (!isset($columnsModel) || empty($columnsModel)) {
            foreach ($columnsOld as $i => $column) {
                $attribute = '';
                $class = $column['class'];

                if (FHtml::field_exists($column, 'attribute'))
                    $attribute = $column['attribute'];

                if (empty($attribute))
                    continue;

                if (!FHtml::isInArray($class, ['*SerialColumn', '*CheckboxColumn', '*ExpandRowColumn']) && (FHtml::field_exists($column, 'attribute') || empty($column->attribute))) {
                    $model = FHtml::getModel('setting_schema', '', []);
                    $model = new models\SettingsSchema();
                    $model->object_type = $object_type;
                    $model->is_system = true;
                    $model->is_active = true;
                    $model->is_column = true;

                    if (FHtml::field_exists($column, 'group'))
                        $model->is_group = $column['group'];

                    if (FHtml::field_exists($column, 'attribute'))
                        $model->name = $column['attribute'];

                    $model->sort_order = $i;
                    $model->save();
                }
            }

            FHtml::populateObjectSchema($object_type);

            return $columnsOld;
        }

        $result = [];

        foreach ($columnsOld as $i => $column) {
            if (!FHtml::field_exists($column, 'class'))
                continue;

            $class = $column['class'];
            if (FHtml::isInArray($class, ['*SerialColumn', '*CheckboxColumn', '*ExpandRowColumn']))
                $result[] = $column;
        }

        $displayedColumns = [];

        foreach ($columnsModel as $col) {
            if ($col->is_column) {
                $displayedColumns[] = $col->name;

                $editor = Editable::INPUT_TEXTAREA;

                $widgetEditor = null;
                $filterData = null;
                $filterType = null;
                $is_group = null;
                $width = '50px';
                if ($col->name == 'id') {
                    $hAlign = 'center';
                } else if (StringHelper::startsWith($col->dbType, 'varchar') && !StringHelper::startsWith($col->dbType, 'varchar(100)') && !FHtml::isInArray($col->name, FHtml::getFIELDS_GROUP(), $moduleName)) {
                    $hAlign = 'left';
                    if (empty($col->editor)) {
                        if (FHtml::isInArray($col->name, FHtml::FIELDS_IMAGES, $moduleName)) {
//                            $editor = Editable::INPUT_WIDGET;
//                            $widgetEditor = FFileInput::className();
                            $editor = \kartik\editable\Editable::INPUT_TEXTAREA;
                        } else
                            $editor = \kartik\editable\Editable::INPUT_TEXTAREA;
                    }
                } else if (FHtml::isInArray($col->name, ['*color'])) {
                    $hAlign = 'center';
                    $filterType = null;
                    $width = '5%';
                    if (empty($col->editor))
                        $editor = \kartik\editable\Editable::INPUT_COLOR;
                } else if (StringHelper::startsWith($col->dbType, 'date')) {
                    $hAlign = 'right';
                    if (empty($col->editor))
                        $editor = \kartik\editable\Editable::INPUT_DATE;
                } else if (StringHelper::startsWith($col->dbType, 'int') || StringHelper::startsWith($col->dbType, 'float') || StringHelper::startsWith($col->dbType, 'bigint')) {
                    $hAlign = 'right';
                    if (empty($col->editor))
                        $editor = \kartik\editable\Editable::INPUT_RANGE;
                } else {
                    $hAlign = 'center';
                    if (empty($col->editor)) {
                        if (StringHelper::startsWith($col->dbType, 'tinyint(1)')) {
//                            $result[] = [
//                                'class'=>'kartik\grid\BooleanColumn',
//                                'attribute'=> $col->name,
//                                'vAlign' => 'middle',
//                            ];
//                            continue;
                            $editor = \kartik\editable\Editable::INPUT_SWITCH;
                            $filterType = GridView::FILTER_CHECKBOX_X;
                        } else {
                            $filterType = GridView::FILTER_SELECT2;
                            $filterData = FHtml::getComboArray($moduleName, $moduleName, $col->name, true, 'id', 'name');
                            $editor = \kartik\editable\Editable::INPUT_SELECT2;
                        }
                    }
                }

                if ($col->is_group) {
                    $hAlign = 'left';
                    $is_group = true;
                }

                $result[] = [ //name: image, dbType: varchar(300), phpType: string, size: 300, allowNull: 1
                    'class' => FHtml::getColumnClass($moduleName, $col->name, $form_type),
                    'format' => 'html',
                    'attribute' => $col->name,
                    'visible' => FHtml::isVisibleInGrid($moduleName, $col->name, $form_type, true),
                    'value' => function ($model, $key, $index, $column) {
                        $content = FHtml::getFieldValue($model, $column->attribute);
                        $showType = FHtml::getShowType($model, $column->attribute);
                        $table = str_replace('-', '_', FHtml::getTableName($model));

                        return FHtml::showContent($content, $showType, $table, $column->attribute, '', '', '', '');
                    },
                    'group' => $is_group,
                    'hAlign' => $hAlign,
                    'vAlign' => 'middle',
                    'width' => $width,
                    'editableOptions' => [
                        'size' => 'md',
                        'inputType' => $editor,
                        'widgetClass' => $widgetEditor,
                        'options' => [
                            'data' => $filterData,
                            'pluginOptions' => [
                            ]
                        ]
                    ],
                    'filterType' => $filterType,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filter' => $filterData,
                ];
            }
        }

        foreach ($columnsOld as $i => $column) {
            if (!FHtml::field_exists($column, 'class'))
                continue;

            if (FHtml::field_exists($column, 'attribute') && in_array($column['attribute'], $displayedColumns))
                continue;

            $class = $column['class'];
            if (FHtml::isInArray($class, ['*SerialColumn', '*CheckboxColumn', '*ExpandRowColumn']))
                continue;


            $class = $column['class'];
            if (FHtml::isInArray($class, ['*DataColumn']) && (FHtml::field_exists($column, 'attribute') && empty($column['attribute'])))
                $result[] = $column;

            if (FHtml::isInArray($class, ['*FormulaColumn', '*ActionColumn']))
                $result[] = $column;
        }

        return $result;
    }

    public static function buildEditor1($model, $attribute, $form, $editor = '', $lookup = '', $options = null)
    {
        ob_start(); // clear buffer string
        $editor = strtolower($editor);

        if (empty($lookup)) // $lookup is empty means it will get data from object_settings table
            $lookup = $model->getTableName();
        else
            $lookup = '@' . $lookup; // if not empty, means it will take from real table

        if ($editor == FHtml::EDITOR_SELECT) {
            echo $lookup;
            echo $editor;
            die;
        }
        if (in_array($editor, ['datetime', FHtml::EDITOR_DATETIME])) {
            $result = DateTimePicker::widget(array_merge(['pluginOptions' => $options], ['type' => DateTimePicker::TYPE_INPUT, 'model' => $model, 'attribute' => $attribute, 'name' => $attribute]));
        } else if (in_array($editor, ['time', FHtml::EDITOR_TIME])) {
            $result = TimePicker::widget(array_merge(['pluginOptions' => $options], ['type' => DateTimePicker::TYPE_INPUT, 'model' => $model, 'attribute' => $attribute, 'name' => $attribute]));
        } else if (in_array($editor, ['date', FHtml::EDITOR_DATE])) {
            $result = DatePicker::widget(array_merge(['pluginOptions' => $options], ['type' => DateTimePicker::TYPE_INPUT, 'model' => $model, 'attribute' => $attribute, 'name' => $attribute]));
        } else if (in_array($editor, ['file', 'upload', FHtml::EDITOR_FILE])) {
            $result = FFileInput::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'pluginOptions' => ['maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['accept' => 'image/*', 'multiple' => false], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]));
        } else if (in_array($editor, ['image', 'thumbnail', FHtml::EDITOR_FILE])) {
            $result = FFileInput::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'pluginOptions' => ['model' => $model, 'form' => $form, 'attribute' => $attribute, 'name' => $attribute, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['accept' => 'image/*', 'multiple' => false], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]));
        } else if (in_array($editor, ['select', FHtml::EDITOR_SELECT])) {
            $result = \kartik\widgets\Select2::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'data' => FHtml::getComboArray($lookup, $lookup, $attribute, true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]));
        } else if (in_array($editor, ['selectmany', FHtml::EDITOR_SELECT])) {
            $result = \kartik\widgets\Select2::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'data' => FHtml::getComboArray($lookup, $lookup, $attribute, true, 'id', 'name'), 'options' => ['multiple' => true], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]));
        } else if (in_array($editor, ['bool', 'boolean', 'checkbox', FHtml::EDITOR_BOOLEAN])) {
            $result = \kartik\checkbox\CheckboxX::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'pluginOptions' => ['model' => $model, 'form' => $form, 'attribute' => $attribute, 'name' => $attribute, 'theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]));
        } else if (in_array($editor, ['text', 'input'])) {
            $result = Html::textarea($attribute, FHtml::getFieldValue($model, $attribute), array_merge($options, ['class' => 'form-control', 'rows' => 3]));
        } else if (in_array($editor, ['html', FHtml::EDITOR_HTML])) {
            $result = FCKEditor::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal']));
        } else if (in_array($editor, ['numeric', 'currency', FHtml::EDITOR_NUMERIC])) {
            $result = MaskedInput::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'clientOptions' => ['alias' => 'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]));
        } else if (in_array($editor, ['slide', FHtml::EDITOR_SLIDE])) {
            $result = Slider::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'sliderColor' => Slider::TYPE_GREY, 'handleColor' => Slider::TYPE_DANGER, 'pluginOptions' => ['min' => 0, 'max' => 100, 'step' => 1]]));
        } else if (in_array($editor, ['rate', FHtml::EDITOR_RATE])) {
            $result = StarRating::widget(array_merge(['pluginOptions' => $options], ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'pluginOptions' => ['stars' => 5, 'min' => 0, 'max' => 5, 'step' => 1, 'showClear' => true, 'showCaption' => true, 'defaultCaption' => '{rating}', 'starCaptions' => [0 => '', 1 => 'Poor', 2 => 'OK', 3 => 'Good', 4 => 'Super', 5 => 'Extreme']]]));
        } else {
            $result = Html::textInput($attribute, FHtml::getFieldValue($model, $attribute), array_merge(['pluginOptions' => $options], ['class' => 'form-control']));
        }

        ob_end_clean();
        return $result;
    }

    public static function buildEditor($model, $attribute, $form, $editor = '', $lookup = '', $field = null)
    {
        $result = '';

        if (empty($lookup)) // $lookup is empty means it will get data from object_settings table
            $lookup = $model->getTableName();
        else {
            $lookup = '@' . $lookup; // if not empty, means it will take from real table
        }

        if (isset($form)) {
            if (!isset($field)) {
                $field = $form->field($model, $attribute);
            }

            if (in_array($editor, ['date', FHtml::EDITOR_DATE])) {
                $result = $field->widget(DatePicker::className(), []);
            } else if (in_array($editor, ['datetime', FHtml::EDITOR_DATETIME])) {
                $result = $field->widget(DateTimePicker::className(), []);
            } else if (in_array($editor, ['time', FHtml::EDITOR_TIME])) {
                $result = $field->widget(TimePicker::className(), []);
            } else if (in_array($editor, ['file', 'upload', FHtml::EDITOR_FILE])) {
                $result = $field->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['accept' => 'image/*', 'multiple' => false], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]);
            } else if (in_array($editor, ['image', FHtml::EDITOR_FILE])) {
                $result = $field->widget(\common\widgets\FFileInput::className(), ['pluginOptions' => ['model' => $model, 'form' => $form, 'attribute' => $attribute, 'name' => $attribute, 'maxFileSize' => FHtml::config('FILE_SIZE_MAX', 4000000), 'options' => ['accept' => 'image/*', 'multiple' => false], 'showPreview' => true, 'showCaption' => false, 'showRemove' => true, 'showUpload' => true, 'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]]]);
            } else if (in_array($editor, ['select', FHtml::EDITOR_SELECT])) {
                $result = $field->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray($lookup, $lookup, $attribute, true, 'id', 'name'), 'options' => ['multiple' => false], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]);
            } else if (in_array($editor, ['selectmany', FHtml::EDITOR_SELECT])) {
                $result = $field->widget(\kartik\widgets\Select2::className(), ['data' => FHtml::getComboArray($lookup, $lookup, $attribute, true, 'id', 'name'), 'options' => ['multiple' => true], 'pluginOptions' => ['allowClear' => true, 'tags' => true]]);
            } else if (in_array($editor, ['bool', 'boolean', 'checkbox', FHtml::EDITOR_BOOLEAN])) {
                $result = $field->widget(\kartik\checkbox\CheckboxX::className(), ['pluginOptions' => ['model' => $model, 'form' => $form, 'attribute' => $attribute, 'name' => $attribute, 'theme' => 'krajee-flatblue', 'size' => 'md', 'threeState' => false]]);
            } else if (in_array($editor, ['textarea', 'description', 'overview'])) {
                $result = $field->textarea(['rows' => 3]);
            } else if (in_array($editor, ['html', 'content', 'note', FHtml::EDITOR_TEXT])) {
                $result = $field->widget(FCKEditor::className(), ['model' => $model, 'attribute' => $attribute, 'name' => $attribute, 'options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal']);
            } else if (in_array($editor, ['numeric', 'currency', 'int', 'float', 'double', FHtml::EDITOR_NUMERIC])) {
                $result = $field->widget(\yii\widgets\MaskedInput::className(), ['clientOptions' => ['alias' => 'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]]);
            } else if (in_array($editor, ['rate', FHtml::EDITOR_RATE])) {
                $result = $field->widget(StarRating::className(), ['pluginOptions' => ['stars' => 5, 'min' => 0, 'max' => 5, 'step' => 1, 'showClear' => true, 'showCaption' => true, 'defaultCaption' => '{rating}', 'starCaptions' => [0 => '', 1 => 'Poor', 2 => 'OK', 3 => 'Good', 4 => 'Super', 5 => 'Extreme']]]);
            } else {
                $result = $field->textInput();
            }
        } else {

        }

        return $result;
    }

    public static function buildEditableOptionsInGridColumn($model, $column, $dbType = '')
    {
        if ($model->editor == 'boolean' || $model->editor == FHtml::EDITOR_BOOLEAN) {
            return [
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_SWITCH,
                'widgetClass' => SwitchInput::className(),
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ];
        } else if (!empty($model->lookup) && ($model->editor == 'select' || $model->editor == FHtml::EDITOR_SELECT)) {
            $data = FHtml::getComboArray($model->lookup, '', '', true, 'id', 'name');
            //self::var_dump($data);
            return [
                'size' => 'md',
                'inputType' => 'dropDownList',
                'widgetClass' => Select2::className(),
                'data' => $data,
                'displayValueConfig' => $data, 'options' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                    ]
                ]
            ];
        } else {
            return [
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                'widgetClass' => 'kartik\datecontrol\InputControl',
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ];
        }
    }


    public static function getGoogleLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos('google.com', $value) > 0)
            $value = 'https://www.google.com/' . $value;
        return $value;
    }

    public static function showSocialLinks($array, $fields = [], $showEmptyValue = true)
    {
        $result = '';
        if (!is_array($array)) {
            if (empty($fields))
                $fields = ['fb', 'tw', 'gp', 'facebook', 'twitter', 'google', 'linkedin'];

            $array = self::getFieldArray($array, $fields);
        }

        if (is_array($array)) {
            foreach ($array as $id => $value) {
                if (!$showEmptyValue && empty($value))
                    continue;
                if (empty($value))
                    $value = '#';

                if ($id == 'facebook' || $id == 'fb') {
                    $css = 'fb';
                    $value = self::getFacebookLink($value);
                } else if ($id == 'twitter' || $id == 'tw') {
                    $css = 'tw';
                    $value = self::getTwitterLink($value);
                } else if ($id == 'linkedin' || $id == 'li') {
                    $css = 'li';
                    $value = self::getLinkedInLink($value);
                } else if ($id == 'google' || $id == 'google') {
                    $css = 'gp';
                    $value = self::getYoutubeLink($value);
                } else
                    $css = '';

                if (empty($value)) {
                    $css .= ' disabled';
                    $value = '#';
                }

                $result .= str_replace(['{css}', '{id}', '{value}'], [$css, $id, $value], '<li><a data-placement="top" data-toggle="tooltip" class="{css} tooltips" data-original-title="{id}" href="{value}"><i class="fa fa-{id}"></i></a></li>');
            }
        }
        if (!empty($result))
            $result = '<ul class="list-inline team-social">' . $result . '</ul>';
        return $result;
    }

    public static function getFacebookLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos('facebook.com', $value) == 0)
            $value = 'https://www.facebook.com/' . $value;
        return $value;
    }

    public static function getTwitterLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos('twitter.com', $value) == 0) {
            if (is_numeric($value))
                $value = 'https://www.twitter.com/intent/user?user_id=' . $value;
            else
                $value = 'https://www.twitter.com/' . $value;
        }

        return $value;
    }

    public static function getLinkedInLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos('linkedin.com', $value) === false) {
            $value = 'https://www.linkedin.com/in/' . $value;
        }
        return $value;
    }

    public static function getYoutubeLink($value)
    {
        if (empty($value))
            return '#';
        if (strpos('youtube', $value) == 0)
            $value = 'https://www.youtube.com/channel/' . $value;
        return $value;
    }

    public static function decode($string, $alwaysArray = false)
    {
        if (empty($string))
            return $string;

        if (is_array($string))
            return $string;

        if (self::is_json($string))
            return json_decode($string, true);
        else if (strpos($string, ',') !== false) {
            return explode(',', $string);
        } else if (strpos($string, ';') !== false)
            return explode(';', $string);
        else {
            if ($alwaysArray)
                return explode(',', $string);
            else
                return $string;
        }
    }

    public static function is_json($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public static function encode($string, $type = 'json')
    {
        if (is_string($string))
            $string = trim($string);

        if ($type == 'json')
            return json_encode($string);

        if (is_array($string) && $type == 'array')
            return implode(',', $string);

        return $string;
    }

    public static function generateRegisterCode()
    {
        $s = strtoupper(md5(time() . rand(1, 100)));
        return $s;
    }

    //Aux function
    public static function generateActivationCode($email)
    {
        $s = strtoupper(md5(uniqid(rand(), true)));
        $e = strtoupper(md5($email));
        return substr($s . $e, 5, strlen($s));
    }

    #cart
    public static function getConfig($key, $default_value, $params = [], $group = '', $editor = '', $lookup = '')
    {
        return \common\components\FHtml::config($key, $default_value, $params, $group, $editor, $lookup);
    }

    public static function checkToken($token)
    {
        $session = Yii::$app->session;
        $session->open();
        if ($token == $session['token'] && strlen($session['token']) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function generateTransactionId($userId)
    {
        $s = strtoupper(md5(uniqid(rand(), true)));
        return substr($s . $userId, 18, strlen($s));
    }

    public static function addDate($date = '', $diff = '', $format = "Y-m-d")
    {
        if (empty($diff))
            return $date;
        $formatedDate = FHtml::formatDate($date, $format, $to_format = 'Y-m-d H:i:s');
        $newdate = strtotime($diff, strtotime($formatedDate));
        return date($format, $newdate);
    }

    public static function formatDate($date, $from_format = 'Y-m-d', $to_format = 'Y-m-d')
    {
        $date_aux = date_create_from_format($from_format, $date);
        return date_format($date_aux, $to_format);
    }

    public static function getImagePath($file, $model_dir = '')
    {
        $baseUpload = Yii::getAlias('@' . UPLOAD_DIR);
        if (is_file($baseUpload . DS . $model_dir . DS . $file)) {
            $image_path = $baseUpload . DS . $model_dir . DS . $file;
        } else {
            $image_path = $baseUpload . DS . WWW_DIR . DS . \Globals::NO_IMAGE;
        }
        return $image_path;
    }

    public static function buildTutorJsAttribute($data_step, $data_title, $data_intro = '', $condition = true, $data_position = 'top')
    {
        if (!$condition)
            return '';

        $result = '';
        $result .= " data-step='$data_step' ";
        $content = empty($data_intro) ? $data_title : "<span style='font-weight:bold;color:darkblue'>$data_title</span><br/>$data_intro";
        $result .= " data-intro='$content'";
        $result .= " data-position='$data_position'";

        return $result;
    }

    public static function displayHeadlineTitle($title, $color = 'green')
    {
        $arr = explode(' ', $title);
        if (count($arr) > 1) {
            $arr[1] = '<span class="color-' . $color . '">' . $arr[1] . '</span>';
        }

        return implode(' ', $arr);
    }

    public static function displayProductOverview($item, $type, $alignment, $color)
    {
        if ($type == 'estate') {
            if ($color == 'default') {
                $color = 'green';
            }
            return
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.beds') . ': </span><span class="' . $alignment . '">' . $item->type . ' ' . FHtml::t('estate', 'estate.items') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.baths') . ': </span><span class="' . $alignment . '">' . $item->status . ' ' . FHtml::t('estate', 'estate.items') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.houseSize') . ': </span><span class="' . $alignment . '">' . $item->category_id . ' ' . FHtml::t('estate', 'estate.m2') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.lotSize') . ': </span><span class="' . $alignment . '">' . $item->price . ' ' . FHtml::t('estate', 'estate.m2') . '</span></li>';

        } else
            return $item->overview;
    }

    public static function displayStar($item, $type, $alignment, $color)
    {
        if ($type == 'estate')

            return
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.beds') . ': </span><span class="' . $alignment . '">' . $item->type . ' ' . FHtml::t('estate', 'estate.items') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.baths') . ': </span><span class="' . $alignment . '">' . $item->status . ' ' . FHtml::t('estate', 'estate.items') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.houseSize') . ': </span><span class="' . $alignment . '">' . $item->category_id . ' ' . FHtml::t('estate', 'estate.m2') . '</span></li>' .
                '<li><span class="color-' . $color . '">' . FHtml::t('estate', 'estate.lotSize') . ': </span><span class="' . $alignment . '">' . $item->price . ' ' . FHtml::t('estate', 'estate.m2') . '</span></li>';

        else
            return $item->overview;
    }

    public static function showPayPalButton($description = '', $amount = '', $currency = '', $receiver_email = '', $return_url = '', $cancel_url = '')
    {
        $url = (FHtml::paypalAPILive() == true) ? 'https://www.paypal.com/cgi-bin/webscr' : 'https://sandbox.paypal.com/cgi-bin/webscr';
        $receiver_email = empty($receiver_email) ? FHtml::paypalAPIEmail() : $receiver_email;
        $currency = empty($currency) ? FHtml::settingCurrency() : $currency;
        $return_url = empty($return_url) ? FHtml::currentHost() . FHtml::createUrl('ecommerce/order/complete') : $return_url;
        $cancel_url = empty($cancel_url) ? FHtml::currentHost() . FHtml::createUrl('ecommerce/order/checkout') : $cancel_url;
        $amount = empty($amount) ? 1 : $amount;
        $description = empty($description) ? FHtml::t('common', 'Have fun') : $description;

        $result = "<form action='$url' method='post'>";
        $result .= "<input type='hidden' name='business' value='$receiver_email'>";
        $result .= "<input type='hidden' name='cmd' value='_xclick'>";
        $result .= "<input type='hidden' name='item_name' value='$description'>";
        $result .= "<input type='hidden' name='amount' value='$amount'>";

        $result .= "<input type='hidden' name='currency_code' value='$currency'>";
        $result .= "<input type='hidden' name='return' value='$return_url'>";
        $result .= "<input type='hidden' name='cancel_url' value='$cancel_url'>";
        $result .= '<input class="sm-margin-bottom-10 pull-right" type="image" src="http://www.paypalobjects.com/en_US/i/btn/x-click-but23.gif" border="0" height="60" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">';

        $result .= '</form>';

        return $result;
    }

    public static function showEditor($value, $field = '', $id = '', $object_type = '', $css = 'editable-{field}', $edit_type = 'textarea', $model = null)
    {
        $t = 'model_id = "' . $id . '" ';
        $t .= 'model_field = "' . $field . '" ';
        $t .= 'object_type = "' . $object_type . '" ';
        $key = '';
        $data = [];

        if (FHtml::isInArray($field, FHtml::getFIELDS_GROUP(), $object_type)) {
            $data = FHtml::getComboArray($key, $object_type, $field);
        }

        if (empty($value))
            $value = FHtml::getRequestParam($field);

        if (FHtml::isInArray($field, FHtml::FIELDS_PRICE)) {
            $edit_type = FHtml::EDITOR_NUMERIC;
        }

        if (is_array($value))
            $value = implode(',', $value);

        if ($edit_type == FHtml::EDITOR_SELECT ||  !empty($data)) {
            $result = Html::dropDownList('{control_name}', $value,
                $data, ['class' => 'form-control {css} ' . $css, 'value' => $value, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => '{uid}-{id}-{field}', 'placeholder' => FHtml::t('common', BaseInflector::camel2words($field))]);
        } else if ($edit_type == FHtml::EDITOR_NUMERIC || $edit_type == FHtml::SHOW_NUMBER) {
            $result = Html::textInput('{control_name}', $value,
                ['class' => 'form-control {css} ' . $css, 'value' => $value, 'type' => 'number', 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => '{uid}-{id}-{field}', 'placeholder' => FHtml::t('common', BaseInflector::camel2words($field))]);
        } else if ($edit_type == FHtml::EDITOR_DATE || $edit_type == FHtml::SHOW_DATE) {
            $result = Html::textInput('{control_name}', $value,
                ['class' => 'form-control {css} ' . $css, 'value' => $value, 'type' => 'date', 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => '{uid}-{id}-{field}', 'placeholder' => FHtml::t('common', BaseInflector::camel2words($field))]);

        } else if ($edit_type == FHtml::EDITOR_BOOLEAN || $edit_type == FHtml::SHOW_BOOLEAN || $edit_type == FHtml::SHOW_ACTIVE) {
            $data = [FHtml::t('common', 'Not ' . $field), FHtml::t('common', $field)];
            $result = Html::dropDownList('{control_name}', $value,
                $data, ['class' => 'form-control {css} ' . $css, 'value' => $value, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => '{uid}-{id}-{field}', 'placeholder' => FHtml::t('common', BaseInflector::camel2words($field))]);

        } else if (FHtml::isInArray($field, array_merge(FHtml::FIELDS_OVERVIEW, FHtml::FIELDS_HTML)) && FHtml::currentAction() != 'index') {
            $result = Html::textarea('{control_name}', $value,
                ['class' => 'form-control {css} ' . $css, 'value' => $value, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => '{uid}-{id}-{field}', 'placeholder' => FHtml::t('common', BaseInflector::camel2words($field))]);
        } else {
            $result = Html::textInput('{control_name}', $value,
                ['class' => 'form-control {css} ' . $css, 'value' => $value, 'object_type' => $object_type, 'model_id' => $id, 'model_field' => $field, 'id' => '{uid}-{id}-{field}', 'placeholder' => FHtml::t('common', BaseInflector::camel2words($field))]);
            //$result = '<input class="editable-{field} form-control {css}" {attr} type="textarea" style="height:30px;padding:5px" id="{id}-{field}" name="{id}-{field}" value="{value}" />';
        }
        $result = str_replace('{attr}', $t, $result);
        return $result;
    }

    public static function showEditorButton($canEdit = true)
    {
        if ($canEdit != true)
            return '';

        $result = '<span onMouseOver="this.style.cursor=\'pointer\'" onclick="showEditor(\'{id}-{field}\')" style="font-size:8pt;color:lightgrey;margin-left:5px" class="glyphicon glyphicon-pencil"></span>';
        return $result;
    }

    public static function showContentEditable($content, $value, $field = '', $id = '', $object_type = '', $edit_type = 'textarea', $showSaveButtons = true, $showEditButton = false, $canEdit = true, $css = '', $model = null)
    {
        if ($canEdit != true || !FHtml::isAuthorized(FHtml::ACTION_EDIT, $object_type, $field))
            return $content;

        if (empty($content))
            $content = FHtml::NULL_VALUE;

        if (is_array($value))
            $value = FHtml::encode($value);

        $result = '';
        $rand = rand(0, 100000);

        if ($showEditButton)
            $result .= $content . ' ' . self::showEditorButton($canEdit);
        else
            $result = '<span id = "{uid}-{id}-{field}-label" onMouseOver="this.style.cursor=\'pointer\'" onclick="showEditor(\'{uid}-{id}-{field}\')">{content}</span>';

        $result .= '<div style="display:none" class="form-input input-append {css}" id="{uid}-{id}-{field}-form">'
            . self::showEditor($value, $field, $id, $object_type, 'editable-{field}', $edit_type, $model)
            . '';
        if ($showSaveButtons)
            $result .= '<span class="btn btn-xs btn-success glyphicon glyphicon-ok" onclick="saveEditor(\'{uid}-{id}-{field}\')"></span>';

        $result .= '<span class="btn btn-xs btn-default glyphicon glyphicon-remove" onclick="closeEditor(\'{uid}-{id}-{field}\')"></span>';
        $result .= '</div>';

        $result = str_replace('{id}', $id, $result);

        $result = str_replace('{field}', $field, $result);
        $result = str_replace('{value}', $value, $result);
        $result = str_replace('{css}', $css, $result);
        $result = str_replace('{content}', $content, $result);
        $result = str_replace('{uid}', $rand, $result);

        return $result;
    }

    public static function showBooleanEditable($content, $value, $field = '', $id = '', $object_type = '', $canEdit = true)
    {
        if ($canEdit != true || !FHtml::isAuthorized(FHtml::ACTION_EDIT, $object_type, $field))
            return $content;

        $result = '';
        $rand = rand(0, 100000);

        $result = '<span id = "{uid}-{id}-{field}" value="{value}" model_id="{id}" model_field="{field}" object_type="{object_type}" onMouseOver="this.style.cursor=\'pointer\'" onclick="saveBoolean(\'{uid}-{id}-{field}\')">{content}</span>';

        $result = str_replace('{id}', $id, $result);
        $result = str_replace('{field}', $field, $result);
        $result = str_replace('{value}', $value, $result);
        $result = str_replace('{content}', $content, $result);
        $result = str_replace('{uid}', $rand, $result);
        $result = str_replace('{object_type}', $object_type, $result);

        return $result;
    }

    //2017/3/24
    public static function registerJs($js, $pos = View::POS_END)
    {
        FHtml::currentView()->registerJs($js, $pos);
    }

    public static function registerReadyJs($js, $pos = View::POS_READY)
    {
        FHtml::currentView()->registerJs($js, $pos);
    }

    public static function registerEditorJS($field, $type = FHtml::EDIT_TYPE_INPUT, $container = 'crud-datatable')
    {
        if ($type == FHtml::EDIT_TYPE_INPUT) {
            $js = ' $(".editable-' . $field . '").change(function()
                    {                       
                        $.ajax({
                            url: "' . FHtml::createBaseAPIUrl('change') . '",
                                type: "POST",
                                data: { 
                                    data:  $(this).val(), 
                                    id: $(this).attr("model_id"), 
                                    field:  $(this).attr("model_field"), 
                                    object: $(this).attr("object_type") 
                                    },
                                success: function (data) {
                                    if(data == 1 || data == "") // save true
                                    {
                                        $.pjax.reload({container: \'#' . $container . '\'});
                                        return false;
                                    }
                                    else
                                        alert(data);
                                }
                         })                        
                    });';

            FHtml::currentView()->registerJs($js, View::POS_READY);
        } else if ($type == FHtml::EDIT_TYPE_INLINE) {
            $js = @'function showEditor($editorid) {
                    $("#" + $editorid + "-label").hide();
                    $("#" + $editorid + "-form").show();
                }
                function closeEditor($editorid) {
                    $("#" + $editorid + "-label").show();
                    $("#" + $editorid + "-form").hide();
                }
                function saveEditor($editorid) {
                    $editorid1 = "input[name=\'" + $editorid + "\']:checked";
                    $.ajax({
                            url: "' . FHtml::createBaseAPIUrl('change') . '",
                            type: "POST",
                            data: { 
                                data:  $("#" + $editorid).val(), 
                                id: $("#" + $editorid).attr("model_id"), 
                                field:  $("#" + $editorid).attr("model_field"), 
                                object: $("#" + $editorid).attr("object_type") 
                                },
                            success: function (data) {
                                if(data == 1 || data == "") // save true
                                {
                                    $.pjax.reload({container: \'#' . $container . '\'});
                                    return false;
                                }
                                else
                                    alert(data);
                            }
                         })
                }
                
                function saveBoolean($editorid) {
                    $.ajax({
                            url: "' . FHtml::createBaseAPIUrl('active') . '",
                            type: "POST",
                            data: { 
                                data:  $("#" + $editorid).attr("value"), 
                                id: $("#" + $editorid).attr("model_id"), 
                                field:  $("#" + $editorid).attr("model_field"), 
                                object: $("#" + $editorid).attr("object_type") 
                                },
                            success: function (data) {
                                if(data == 1 || data == "") // save true
                                {
                                    $.pjax.reload({container: \'#' . $container . '\'});
                                    return false;
                                }
                                else
                                    alert(data);
                            }
                         })
                }';

            FHtml::currentView()->registerJs($js, View::POS_END);
        }
    }

    //2017/3/6
    public static function registerPlusJS($model, $columns = [], $container = 'crud-datatable', $field_name = '{object}Search[{column}]', $default_fields = [], $excluded_fields = ['sort_order'])
    {
        $modelName = BaseInflector::camelize($model);

        if (empty($model))
            return;

        $t = 'object: "' . $model . '", ';
        $r = '"" ';
        $s = '"" ';
        $s1 = '';
        foreach ($columns as $column) {
            if (FHtml::isInArray($column, $excluded_fields))
                continue;

            if (!StringHelper::startsWith('#', $field_name)) {
                $field_id = '[name=\'' . $field_name . '\']';
                $field_id = str_replace('{object}', $modelName, $field_id);
                $field_id = str_replace('{column}', $column, $field_id);
            } else {
                $field_id = $field_name;
                $field_id = str_replace('{object}', strtolower($modelName), $field_id);
                $field_id = str_replace('{column}', $column, $field_id);
            }

            $field_value = FHtml::getRequestParam($field_name);
            if (!empty($field_value)) { // if existed param in url
                $t .= "$column: '$field_value', ";
            } else {
                $t .= $column . ': $("' . $field_id . '").val(), ';
            }
            $s .= '+ "&' . $column . '=" + ' . '$("' . $field_id . '").val()';
            $s1 .= $field_id . ': $("' . $field_id . '").val(), ';

            $r .= '+ "' . $field_id . ': " + $("' . $field_id . '").val() + " - "';
        }

        $t1 = '';
        $field_names = '';
        if (!empty($default_fields)) {
            foreach ($default_fields as $column => $value) {
                if (FHtml::isInArray($column, $excluded_fields))
                    continue;

                $t1 .= $column . ': "' . $value . '", ';
                $field_names .= $column . ',';

            }
        }

        $t = $t . $t1;

        $zone = FHtml::currentZone();

        $js = @'
            function plus' . $modelName . '() {
                $.ajax({
                        url: "' . FHtml::createBaseAPIUrl('plus', [], $zone) . '",
                        type: "POST",
                        data: { 
                            ' . $t .
            '},
                        success: function (data) {
                            //alert(data);
                            if(data == 1 || data == "") // save true
                            {
                                $.pjax.reload({container: \'#' . $container . '\'});
                                return false;
                            }
                            else
                                alert(data);
                        }
                     })
            }';


        $js .= @'
            function search' . $modelName . '() {
                self.location = "' . str_replace('\\', '/', FHtml::createUrl(FHtml::currentUrlPath(), ArrayHelper::merge(['action' => 'filter'], FHtml::RequestParams($columns)))) . '&" + ' . $s . ';

        }';

        FHtml::currentView()->registerJs($js, View::POS_END);

        self::registerResetJs($model, $default_fields, $container);
        self::registerDeleteJs($model, $container);
    }

    public static function registerResetJs($model, $default_fields = [], $container = 'crud-datatable')
    {
        $modelName = BaseInflector::camelize($model);

        if (empty($model))
            return;
        $t1 = '';
        $field_names = '';
        if (!empty($default_fields)) {
            foreach ($default_fields as $column => $value) {
                $field_names .= $column . ',';
            }
        }

        $zone = FHtml::currentZone();

        $js = @'
            function reset' . $modelName . '($id) {
                $.ajax({
                        url: "' . FHtml::createBaseAPIUrl('reset', [], $zone) . '",
                        type: "POST",
                        data: { 
                            object: "' . $model . '", id: $id, field: "' . $field_names . '"
                        },
                        success: function (data) {
                            //alert(data);
                            if(data == 1 || data == "") // save true
                            {
                                $.pjax.reload({container: \'#' . $container . '\'});
                                return false;
                            }
                            else
                                alert(data);
                        }
                     })
            }';

        FHtml::currentView()->registerJs($js, View::POS_END);
    }


    public static function registerDeleteJs($model, $container = 'crud-datatable')
    {
        $modelName = BaseInflector::camelize($model);

        if (empty($model))
            return;

        $zone = FHtml::currentZone();

        $js = @'
            function delete' . $modelName . '($id) {
                $.ajax({
                        url: "' . FHtml::createBaseAPIUrl('remove', [], $zone) . '",
                        type: "POST",
                        data: { 
                            object: "' . $model . '", id: $id
                        },
                        success: function (data) {
                            //alert(data);
                            if(data == 1 || data == "") // save true
                            {
                                $.pjax.reload({container: \'#' . $container . '\'});
                                return false;
                            }
                            else
                                alert(data);
                        }
                     })
            }';

        FHtml::currentView()->registerJs($js, View::POS_END);
    }

    public static function registerLookup($modelName, $changeFields = [], $search_object, $search_field = 'name', $id_field = 'id', $isMultipleForm = false)
    {
        return self::getSelect2Options($modelName, $changeFields, $search_object, $search_field, $id_field, $isMultipleForm);
    }

    public static function getSelect2LookupAjaxOptions($modelName, $search_object = '', $search_field = 'name')
    {
        $url = self::createBaseAPIUrl('list-lookup', ['search_object' => $search_object, 'search_field' => $search_field]);
        return [
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'No result found !'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
        ];
    }

    public static function getSelect2OnChangeAjaxOptions($modelName, $changeFields = [], $search_object = '', $search_field = 'id', $isMultipleForm = false)
    {
        $urlDetail = self::createBaseAPIUrl('detail-lookup', ['search_object' => $search_object, 'search_field' => $search_field]);
        $populateFields = '';
        $tempIDs = '';
        $controlID = str_replace('_', '', $modelName);

        if (is_array($changeFields)) {
            foreach ($changeFields as $oldField => $newField) {
                if ($isMultipleForm) {
                    $tempIDs .= "var id_" . $oldField . " = this.id.replace('" . $controlID . "', '" . $oldField . "');";
                    $populateFields .= "$('#'+ id_" . $oldField . ").val(data['" . $newField . "']);";
                } else {
                    $populateFields .= "$('#" . $controlID . "-" . $oldField . "').val(data['" . $newField . "']);";
                }
            }
        } else if (is_string($changeFields)) {
            $populateFields = $changeFields; //execute local javascript function
        }

        return ["select2:close" => "function(e) { $tempIDs
                                                     $.ajax({
                                                        url: '$urlDetail',
                                                        type: 'post',
                                                        data: {keys: $(this).val()},
                                                        success: function(data) {console.log('Ok...' + data[0]);$populateFields},
                                                        error: function(data) {
                                                            console.log('Error...'+data);
                                                        },
                                                    });
                                                }"
        ];
    }

    public static function getSelect2Options($modelName, $changeFields = [], $search_object, $search_field = 'name', $id_field = 'id', $isMultipleForm = false)
    {
        $options1 = self::getSelect2LookupAjaxOptions($modelName, $search_object, $search_field);
        $options2 = self::getSelect2OnChangeAjaxOptions($modelName, $changeFields, $search_object, $id_field, $isMultipleForm);
        $result = ['pluginOptions' => $options1, 'pluginEvents' => $options2];
        return $result;
    }

    //2017/3/10: change from a -> button
    public static function showPlusButton($object_type, $is_column = true, $is_search = false)
    {

        $add_t = '';
        $search_t = '';
        if ($is_column === false) {
            $add_t = FHtml::t('common', 'Add');
            $search_t = FHtml::t('common', 'Search');
        }

        if ($is_search == true) {
            $result = '<button class="btn btn-sm btn-success" href="#" onclick="plus' . BaseInflector::camelize($object_type) . '()" value="+"> <span class="glyphicon glyphicon-plus"></span> ' . $add_t . ' </button>';
            $result .= '<a class="btn btn-sm btn-default" style="padding-left:8px;padding-right:8px" href="#" data-pjax="1" onclick="search' . BaseInflector::camelize($object_type) . '()"> <span class="glyphicon glyphicon-search"></span> ' . $search_t . ' </a>';
        } else {
            $result = '<a class="btn btn-sm btn-success" href="#" onclick="plus' . BaseInflector::camelize($object_type) . '()" value="+"> <span class="glyphicon glyphicon-plus"></span> ' . $add_t . ' </a>';
        }
        return $result;
    }

    public static function showPlusForms($model, $columns = [], $container = 'crud-datatable', $field_name = '{object}Search[{column}]', $is_search = true)
    {
        $modelName = BaseInflector::camelize($model);
        $r = '<div class="row" style = "margin:1px; padding:10px; border:1px solid lightgrey; background-color: #eef1f5">';
        foreach ($columns as $column) {
            $field_id = $field_name;
            $field_id = str_replace('{object}', $modelName, $field_id);
            $field_id = str_replace('{column}', $column, $field_id);
            $control = self::showEditor('', $column, '', $model, '');
            $control = str_replace('{control_name}', $field_id, $control);
            $size = '2';
            if (FHtml::isInArray($column, FHtml::getFIELDS_GROUP(), $model))
                $size = '1';
            else
                $size = '2';
            $r .= '<div class="col-md-' . $size . '">' . $control . '</div>';
        }

        $r .= '<div class="col-md-1 pull-right" style="padding-top:3px">' . FHtml::showPlusButton($model, false, $is_search) . '</div>';

        $r .= '</div>';

        return $r;
    }

    public static function showPlusTableRow($model, $columns = [], $container = 'crud-datatable', $field_name = '{object}Search[{column}]', $is_search = true)
    {
        $modelName = BaseInflector::camelize($model);
        $r = '<tr style = "border:1px solid lightgrey; background-color: #eef1f5" id="' . $container . '-forms">';
        $cells = [];
        foreach ($columns as $column) {

            $jump = false;
            $field_id = $field_name;
            $field_id = str_replace('{object}', $modelName, $field_id);
            $field_id = str_replace('{column}', $column, $field_id);
            if (!isset($column)) {
                $control = '<td></td>';
            } else if ($column == 'action') {
                $control = '<td class="skip-export kv-align-center kv-align-middle">' . FHtml::showPlusButton($model, true, $is_search) . '' . '</td>';
            } else if ($column == 'null') {
                $control = '<td></td>';
            } else if ($column == 'group') {
                $control = '<td></td>';
                $jump = true;
            } else {
                $control = self::showEditor('', $column, '', $model, '');
                $control = '<td>' . str_replace('{control_name}', $field_id, $control) . '</td>';
            }
            if ($jump)
                continue;
            $cells[] = $control;
        }
        $r .= implode('', $cells);//implode('', $cells);
        $r .= '</tr>';

        return $r;
    }

    //2017/04/05
    public static function showTags($tags, $url = '', $template = '<span class="badge" style="font-size:120%; margin-right:10px; margin-bottom:15px; padding:5px; background-color: {color}"> {tag} </span>', $color = '#81C6B6') {
        if (empty($tags))
            return '';

        if (is_string($tags))
            $tags = FHtml::decode($tags);
        if (is_string($tags))
            $tags = [$tags => $tags];

        $arr = [];
        foreach ($tags as $id => $tag) {
            $url1= $url;
            $tag = trim($tag);
            if (is_numeric($id))
                $id = $tag;
            $item = str_replace('{color}', $color, $template);
            $item = str_replace('{tag}', '#' . $tag, $item);
            if (!empty($url1)) {
                $url1 = FHtml::createUrl($url1, ['tag' => $id]);
                $item = "<a href='$url1' alt='$tag'>{$item}</a>";
            }

            $arr[] = $item;
        }

        return implode(' ', $arr);
    }

    public static function showGroupHeader($label = null, $content = '')
    {
        $label = FHtml::t('common', $label);
        $label = $label . ' ' . $content;
        return "<div style='font-size:150%; color:#578ebe; border-bottom:1px dashed lightgrey; padding-top:20px; padding-bottom:10px; margin-bottom: 10px'>&nbsp;<br/>{$label}</div>";
    }

    //2017/11/3
    public static function createUploadFileName($model, $image_file, $lower_name = '', $field = '')
    {
        $name = strtolower(str_replace(' ', '-', FHtml::getFieldValue($model, ['name', 'title'])));
        $name = self::toSEOFriendlyString($name);

        return $name . '_' . $lower_name . '_' . $field . '_' . FHtml::getFieldValue($model, ['id']) . '.' . $image_file->extension;
    }

    /**
     * Returns an string clean of UTF8 characters. It will convert them to a similar ASCII character
     * www.unexpectedit.com
     */
    public static function cleanStringforSEO($text)
    {
        // 1) convert á ô => a o
        $text = preg_replace("/[áàâãªä]/u", "a", $text);
        $text = preg_replace("/[ÁÀÂÃÄ]/u", "A", $text);
        $text = preg_replace("/[ÍÌÎÏ]/u", "I", $text);
        $text = preg_replace("/[íìîï]/u", "i", $text);
        $text = preg_replace("/[éèêë]/u", "e", $text);
        $text = preg_replace("/[ÉÈÊË]/u", "E", $text);
        $text = preg_replace("/[óòôõºö]/u", "o", $text);
        $text = preg_replace("/[ÓÒÔÕÖ]/u", "O", $text);
        $text = preg_replace("/[úùûü]/u", "u", $text);
        $text = preg_replace("/[ÚÙÛÜ]/u", "U", $text);
        $text = preg_replace("/[’‘‹›‚]/u", "'", $text);
        $text = preg_replace("/[“”«»„]/u", '"', $text);
        $text = str_replace("–", "-", $text);
        $text = str_replace(" ", " ", $text);
        $text = str_replace("ç", "c", $text);
        $text = str_replace("Ç", "C", $text);
        $text = str_replace("ñ", "n", $text);
        $text = str_replace("Ñ", "N", $text);

        //2) Translation CP1252. &ndash; => -
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
        $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
        $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
        $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
        $trans[chr(134)] = '&dagger;';    // Dagger
        $trans[chr(135)] = '&Dagger;';    // Double Dagger
        $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
        $trans[chr(137)] = '&permil;';    // Per Mille Sign
        $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
        $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
        $trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE
        $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
        $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
        $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
        $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
        $trans[chr(149)] = '&bull;';    // Bullet
        $trans[chr(150)] = '&ndash;';    // En Dash
        $trans[chr(151)] = '&mdash;';    // Em Dash
        $trans[chr(152)] = '&tilde;';    // Small Tilde
        $trans[chr(153)] = '&trade;';    // Trade Mark Sign
        $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
        $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
        $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
        $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
        $trans['euro'] = '&euro;';    // euro currency symbol
        ksort($trans);

        foreach ($trans as $k => $v) {
            $text = str_replace($v, $k, $text);
        }

        // 3) remove <p>, <br/> ...
        $text = strip_tags($text);

        // 4) &amp; => & &quot; => '
        $text = html_entity_decode($text);

        // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
        $text = preg_replace('/[^(\x20-\x7F)]*/', '', $text);

        $targets = array('\r\n', '\n', '\r', '\t');
        $results = array(" ", " ", " ", "");
        $text = str_replace($targets, $results, $text);

        //XML compatible
        /*
        $text = str_replace("&", "and", $text);
        $text = str_replace("<", ".", $text);
        $text = str_replace(">", ".", $text);
        $text = str_replace("\\", "-", $text);
        $text = str_replace("/", "-", $text);
        */

        return ($text);
    }

    public static function toSEOFriendlyString($str, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $str = self::cleanStringforSEO($str);

        $str = self::cleanString($str);

        return $str;
    }

    public static function cleanString($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    /* ham xoa dau tieng viet*/
    function cleanStringVietnamese($cs, $tolower = false){
        /*Mảng chứa tất cả ký tự có dấu trong Tiếng Việt*/
        $marTViet=array(
            "à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
            "ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề",
            "ế","ệ","ể","ễ",
            "ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ",
            "ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă",
            "Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ"," ",",",":",'"',".","'","&","%","*","@","!","#","$","^","(",")","/","\\",")","(","_","{","}","[","]","?","<",">","|","+","=","`");
        /*Mảng chứa tất cả ký tự không dấu tương ứng với mảng $marTViet bên trên*/
        $marKoDau=array("a","a","a","a","a","a","a","a","a","a","a",
            "a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o",
            "o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A",
            "A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D","-");
        if ($tolower) {
            return strtolower(str_replace($marTViet,$marKoDau,$cs));
        }
        return str_replace($marTViet,$marKoDau,$cs);
    }

    public static function saveModelAjax($controller, $model, $modelMeta = null) {
        $request = Yii::$app->request;
        $id = FHtml::getFieldValue($model, ['id']);
        /*
            *   Process for ajax request
            */
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isGet){
            return [
                'title'=> FHtml::t($controller->moduleName)." #".$id,
                'content'=>$controller->renderPartial('update', [
                    'model' => $model, 'modelMeta' => $modelMeta,
                ]),
                'footer'=> Html::button(FHtml::t('Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::button(FHtml::t('Save'),['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }else if($model->load($request->post()) && $model->save()){
            return [
                'forceReload'=>'#crud-datatable-pjax',
                'title'=> FHtml::t($controller->moduleName)." #".$id,
                'content'=>$controller->renderAjax('view', [
                    'model' => $model, 'modelMeta' => $modelMeta,
                ]),
                'footer'=> Html::button(FHtml::t('Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::a(FHtml::t('update'),['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];
        }else{
            return [
                'title'=> FHtml::t($controller->moduleName)." #".$id,
                'content'=>$controller->renderAjax('update', [
                    'model' => $model, 'modelMeta' => $modelMeta
                ]),
                'footer'=> Html::button(FHtml::t('Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::button(FHtml::t('Save'),['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }
    }


    public static function getMenuUrls($menu = null) {
        if (!isset($menu))
            $menu = FHtml::backendMenu();

        $arr = [];
        foreach ($menu as $item) {
            if (key_exists('url', $item))
                $arr[] = $item['url'];
            if (key_exists('children', $item))
                $arr = array_merge($arr, self::getMenuUrls($item['children']));
        }

        return $arr;
    }

    public static function getModuleControllersFromUrls($urls = null) {
        if (!isset($urls))
            $urls = self::getMenuUrls();

        $arr = [];
        foreach ($urls as $url) {
            $arr1 = explode('/', $url);

            if (count($arr1) > 7) {
                $module = $arr1[5]; $object_type = $arr1[6];
            }
            else {
                $module = ''; $object_type = $arr1[5];
            }

            if (key_exists($module, $arr))
            {
                $arr[$module] = array_merge($arr[$module], [$object_type]);
            } else {
                $arr = array_merge($arr, [$module => [$object_type]]);
            }
        }

        return $arr;
    }

    public static function showObjectConfigLink($model, $name_fields = FHtml::FIELDS_NAME) {
        $object_id = FHtml::getFieldValue($model, ['id']);
        $object_type = FHtml::getTableName($model);
        $name = FHtml::getFieldValue($model, $name_fields);
        $link = !FHtml::isRoleModerator() ? '' : FHtml::createLink('object-config/view', ['object_id' => $object_id, 'object_type' => $object_type], BACKEND, '<span class="glyphicon glyphicon-cog"></span>', '_blank', '');
        return "$name &nbsp; $link";
    }

    public static function showArrayAsTable($arr) {
        $str = "<table class='table table-bordered table-condensed'><tbody>";
        foreach ($arr as $key => $val) {
            $str .= "<tr>";
            $str .= "<td class='col-md-3'>$key</td>";
            $str .= "<td>";
            if (is_array($val)) {
                if (!empty($val)) {
                    $str .= self::showArrayAsTable($val);
                }
            } else {
                $str .= "<strong>$val</strong>";
            }
            $str .= "</td></tr>";
        }
        $str .= "</tbody></table>";

        return $str;
    }

    public static function showJsonAsTable($jsonText = '')
    {
        $arr = json_decode($jsonText, true);
        $html = "";
        if ($arr && is_array($arr)) {
            $html .= self::showArrayAsTable($arr);
        }
        return $html;
    }

    public static function showObjectAsTable($changedItems, $columns = [], $fieldName = ['name']) {
        if (is_object($changedItems))
            $changedItems = FHtml::getFieldValue($changedItems, $fieldName);

        if (is_string($changedItems))
            $changedItems = FHtml::decode($changedItems);

        $result = '';
        foreach ($changedItems as $item) {
            $result .= "<tr>";
            $i = 0;

            foreach ($columns as $column) {
                $class = $i == 0 ? 'col-md-2' : '';
                $result .= "<td class='$class'>$item[$i]</td>";
                $i+= 1;
            }
            $result .= "</tr>";
        }
        if (!empty($result))
            $result = "<table class='table table-bordered table-condensed'>$result</table>";
        return $result;
    }

    public static function showLangsMenu($showFlag = false) {
        if (!FHtml::isLanguagesEnabled())
            return '';

        if(!$showFlag){
            $lang = FHtml::currentLang();
            $lang_array = FHtml::applicationLangsArray();
            $result = "<a style='color:white !important;' href='javascript:;' class='dropdown-toogle' data-toogle='dropdown' data-hover='dropdown' data-close-others='true'><span class='username username-hide-mobile'>$lang</span></a>";
            $result .= "<ul class='dropdown-menu dropdown-menu-default'>";

            foreach ($lang_array as $lang_item => $lang_name) {
                $url = FHtml::currentUrl([FHtml::LANGUAGES_PARAM => $lang_item]);
                $result .= "<li><a href='$url'>$lang_name  <span class='pull-right'>$lang_item</span></a></li>";
            }
            $result .= "</ul>";
            return $result;
        }
        if($showFlag){
            //$id = self::currentApplicationId();
            $lang_array = FHtml::applicationLangsArray();
            $result='';
            foreach ($lang_array as $lang_item => $lang_name) {
                $url = FHtml::currentUrl([FHtml::LANGUAGES_PARAM => $lang_item]);
                $image = FHtml::getImageUrl($lang_item.".png", 'www/flag', false);
                $result .= "<li >  <a href='$url' style='padding-right: 0'>  <img width='32' height='20' src='$image' ></a> </li>";
            }
            return $result;
        }
    }

    public static function showEmptyMessage($model = null) {
        if (!empty($model))
            return '';

        $t = FHtml::t('common', 'No data available yet');
        $t = "<div style='color:darkgrey;font-size:20pt;text-align: center; padding:20px'>$t.</div>";
        return $t;
    }

    public static function showPageWidthScript() {
        $zoom = FHtml::settingPageWidth();
        if (!empty($zoom) && !in_array($zoom, ['full', '100%']))
            FHtml::currentView()->registerJs("document.body.style.zoom='$zoom';");
        return '';
    }
}