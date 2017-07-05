<?php
/**
 * Developed by Hung Ho (Steve): ceo@mozagroup.com | hung.hoxuan@gmail.com | skype: hung.hoxuan | whatsapp: +84912738748
 * Software Outsourcing, Mobile Apps development, Website development: Make meaningful products for start-ups and entrepreneurs
 * MOZA TECH Inc: www.mozagroup.com | www.mozasolution.com | www.moza-tech.com | www.apptemplate.co | www.projectemplate.com | www.code-faster.com
 * This is the customized model class for table "<?= $generator->generateTableName($tableName) ?>".
 */

namespace common\components;

use applications\ecommerce\Ecommerce;
use backend\models\ApplicationBase;
use backend\models\AppUser;
use backend\models\Settings;
use backend\modules\cms\models\CmsArticle;
use backend\modules\cms\models\CmsPage;
use backend\modules\cms\models\CmsPortfolio;
use common\components\FConstant;
use common\models\User;
use frontend\components\Helper;
use yii\base\Component;
use common\components\FHtml;
use Yii;
use backend\modules\system\models\Application;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseInflector;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use Mobile_Detect;

class FConfig extends FSecurity
{
    public static function isAjaxRequest() {
        return Yii::$app->request->isAjax;
    }

    public static function isLanguagesEnabled($table = null)
    {
        $result = LANGUAGES_ENABLED;
        if ($result && isset($table) && !empty($table))
            $result = self::isDBLanguagesEnabled($table);

        return $result;
    }

    public static function isDBLanguagesEnabled($table = null)
    {
        $result = DB_LANGUAGES_ENABLED;

        if ($result && isset($table) && !empty($table)) {
            if (is_object($table) || is_array($table))
                $table = FHtml::getTableName($table);
            $result = FHtml::isInArray($table, FHtml::INCLUDED_TABLES_AS_MULTILANGS) || !FHtml::isInArray($table, FHtml::EXCLUDED_TABLES_AS_MULTILANGS);
        }
        return $result;
    }

    public static function isApplicationsEnabled($model = null, $skip_checked = false)
    {
        $result = APPLICATIONS_ENABLED || !empty(DEFAULT_APPLICATION_ID);
        if (!$result) // if false then return immediately
            return $result;

        if (isset($model) && is_object($model)) {
            $model = FHtml::getTableName($model);
        }

        if (is_string($model) && !empty($model) && !$skip_checked) {
            $result = $result && FHtml::field_exists($model, 'application_id') && !FHtml::isInArray($model, FHtml::EXCLUDED_TABLES_AS_APPLICATIONS);
        }

        return $result;
    }

    public static function isCacheEnabled()
    {
        return CACHE_ENABLED;
    }

    public static function isObjectActionsLogEnabled($model = null)
    {
        $result = OBJECT_ACTIONS_ENABLED;
        if (!$result)
            return $result;

        if (isset($model) && is_object($model)) {
            $model = FHtml::getTableName($model);
        }

        if (is_string($model) && !empty($model)) {
            $result = $result && !FHtml::isInArray($model, FHtml::EXCLUDED_TABLES_AS_OBJECT_CHANGES);
        }

        return $result;
    }


    public static function isDBSettingsEnabled()
    {
        return DB_SETTINGS_ENABLED;
    }

    public static function isDynamicFormEnabled($moduleKey = '')
    {
        return DYNAMIC_FORM_ENABLED;
    }

    public static function isSystemAdminEnabled()
    {
        return SYSTEM_ADMIN_ENABLED;
    }

    //2017/5/3
    public static function isDynamicObjectEnabled($moduleKey = '')
    {
        return DYNAMIC_OBJECT_ENABLED;
    }

    public static function currentPageCode($zone = null, $module = null, $controller = null, $action = null) {
        $zone = isset($zone) ? $zone : FHtml::currentZone();

        $module = isset($module) ? $module : BaseInflector::camel2words(FHtml::currentModule());
        $controller = isset($controller) ? $controller : FHtml::currentController();
        $action = isset($action) ? $action : FHtml::currentAction();

        if ($zone == FRONTEND)
            $zone = '';

        if (in_array($controller, ['site']))
            $controller = '';

        if (in_array($action, ['index']))
            $action = '';

        $result = "$zone/$module/$controller/$action";

        $result = trim(str_replace('//', '/', $result), "/");

        if (empty($result))
            $result = 'home';

        return strtolower($result);
    }

    //HungHX: 20160801
    public static function getCurrentPageSize()
    {
        return FHtml::config(FHtml::SETTINGS_PAGE_SIZE, FHtml::DEFAULT_ITEMS_PER_PAGE, null, 'Data', FHtml::EDITOR_NUMERIC);
    }

    public static function currentLang()
    {
        if (FHtml::isLanguagesEnabled()) {
            $lang = FHtml::getRequestParam('lang'); // only return lang if pass in request params
            if (!empty($lang)) {
                return $lang;
            }

            $lang = FHtml::getRequestParam(FHtml::LANGUAGES_PARAM);  // save current lang into session for global uses
            if (!empty($lang)) {
                self::setCurrentLang($lang);
                return $lang;
            }


            $lang = self::Session()->get(FHtml::SETTINGS_LANG);
            if (!empty($lang)) {
                return $lang;
            }

            if (isset(Yii::$app->request->cookies[FHtml::LANGUAGES_PARAM]->value )) //If there is language defined in cookie, use it
            {
                $lang = Yii::$app->request->cookies[FHtml::LANGUAGES_PARAM]->value;
                self::setCurrentLang($lang);
                return $lang;
            }
        }

        $lang = self::settingApplicationLang();
        if (!empty($lang)) {
            self::setCurrentLang($lang);
            return $lang;
        }

        $lang = Yii::$app->language;
        if (!empty($lang)) {
            self::setCurrentLang($lang);
            return $lang;
        }

        return DEFAULT_LANG;
    }

    public static function currentModule()
    {
        $id = Yii::$app->controller->module->id;
        if ($id == ADMIN_DASHBOARD_MODULE || $id == 'app-frontend' || $id == 'app-backend')
            return '';
        return $id;
    }

    // merge Current Url with params
    public static function currentUrl($params = [], $required_index_php = false)
    {
        $url_prefix = '';
        $zone = FHtml::currentZone();
        $params = array_merge(\Yii::$app->getRequest()->getQueryParams(), $params);
        if ($zone == FRONTEND) {
            if ($required_index_php || strpos(FHtml::currentUrlPath(), 'index.php') === false)
                $url_prefix = 'index.php';

            $url = $url_prefix . '/' . Yii::$app->controller->getRoute();
            return FHtml::createUrl($url, $params);
        } else {
            $url = $url_prefix . '/' . Yii::$app->controller->getRoute();


            return FHtml::createUrl($url, $params);
        }
    }

    public static function currentQueryString()
    {
        return \Yii::$app->getRequest()->getQueryString(); // will return var=val,
    }

    public static function currentUrlPath()
    {
        return Yii::$app->getRequest()->getPathInfo(); // will return forum/index,
    }

    public static function setCurrentLang($lang)
    {
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => '_lang',
            'value' => $lang,
        ]));
        self::Session()->remove(FHtml::SETTINGS_LANG);
        self::Session()->set(FHtml::SETTINGS_LANG, $lang);
    }

    public static function currentConfig()
    {
        return self::currentApplication();
    }

    public static function Cache()
    {
        return \Yii::$app->cache;
    }

    public static function paypalAPIUsername()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_USERNAME, PAYPAL_API_USERNAME, [], 'Paypal');
    }

    public static function paypalAPIEmail()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_EMAIL, PAYPAL_API_EMAIL, [], 'Paypal');
    }

    public static function paypalAPIPassword()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_PASSWORD, PAYPAL_API_PASSWORD, [], 'Paypal');
    }

    public static function paypalAPISignature()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_SIGNATURE, PAYPAL_API_SIGNATURE, [], 'Paypal');
    }

    public static function paypalAPILive()
    {
        return self::config(FHtml::SETTINGS_PAYPAL_API_LIVE, PAYPAL_API_LIVE, [], 'Paypal', FHtml::EDITOR_BOOLEAN);
    }

    public static function currentZone()
    {
        $url = \Yii::$app->request->baseUrl;
        if (strpos($url, '/backend/') !== false)
            return BACKEND;
        else
            return FRONTEND;
    }

    //2017.5.4
    private static $detect;

    public static function currentDevice()
    {
        if (!isset($detect))
            $detect = new \common\components\Mobile_Detect();

        return $detect;
    }

    public static function currentController()
    {
        return Yii::$app->controller->id;
    }

    public static function currentControllerObject()
    {
        return Yii::$app->controller;
    }

    public static function currentObjectType()
    {
        return str_replace('-', '_', self::currentController());
    }

    public static function getCurrentMainColor($default_value = '')
    {
        return self::currentApplicationMainColor($default_value);
    }

    public static function currentApplicationMainColor($default_value = '')
    {
        $result = $main_color = FHtml::getApplicationConfig('main_color');
        if (empty($result))
            return $default_value;
        return $result;
    }

    public static function configWelcomeMessage($default_value = WELCOME_MESSAGE)
    {
        return FHtml::config('WELCOME MESSAGE', $default_value);
    }

    public static function currentView()
    {
        return Yii::$app->controller->getView();
    }

    public static function currentAction()
    {
        if (isset(Yii::$app->controller))
            $action = Yii::$app->controller->action->id;
        else
            $action = '';

        return $action;
    }

    public static function isViewAction($action = '') {
        if (empty($action))
            $action = FHtml::currentAction();
        return FHtml::isInArray($action, ['view', 'view-detail']);
    }

    public static function isListAction($action = '') {
        if (empty($action))
            $action = FHtml::currentAction();
        return FHtml::isInArray($action, ['list', 'index']);
    }

    public static function isEditAction($action = '') {
        if (empty($action))
            $action = FHtml::currentAction();
        return FHtml::isInArray($action, ['create', 'update', 'edit', 'delete', 'update-detail']);
    }

    public static function currentAdminModules()
    {
        return self::config(FHtml::SETTINGS_ADMIN_MODULES, DEFAULT_ADMIN_MODULES);
    }

    public static function currentDomain()
    {
        return \Yii::$app->getUrlManager()->getHostInfo();
    }

    public static function currentHost()
    {
        return \Yii::$app->getUrlManager()->getHostInfo();
    }

    public static function currentApplicationId()
    {
        if (!APPLICATIONS_ENABLED && !empty(DEFAULT_APPLICATION_ID)) { //fixed Application ID, always return that value
            $id = DEFAULT_APPLICATION_ID;
            self::setApplicationId($id);
            return $id;
        }

        $id = FHtml::getRequestParam('application_id');
        if (!empty($id)) {
            self::setApplicationId($id);
        }

        $session = self::Session();
        if (isset($session))
            $id = $session->get("application.id");

        if (empty($id)) {
            $id = DEFAULT_APPLICATION_ID;

            self::setApplicationId($id);
        }

        return $id;
    }

    public static function getCurrentLogo($default = '')
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_LOGO, $default);
    }

    public static function settingCompanyLogo()
    {
        return self::getCurrentLogo();
    }

    public static function settingCompanyName()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_NAME, '', [], 'Config');
    }

    public static function settingCompanyCopyRight()
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_COPYRIGHT, '', [], 'Config');
        if (empty($result))
            $result = '@' . date('Y') . ' Copyright by ' . self::settingCompanyName();

        return $result;
    }

    public static function settingCompanyTerms()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_TERMS_OF_SERVICE, '', [], 'Config');
    }

    public static function settingCompanyFacebook($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_FACEBOOK, '', [], 'Config');
        if ($url && !empty($result))
            $result = FHtml::getFacebookLink($result);

        return $result;
    }

    public static function settingCompanyYoutube($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_YOUTUBE, '', [], 'Config');
        if ($url && !empty($result))
            $result = FHtml::getYoutubeLink($result);
        return $result;
    }

    public static function settingCompanyChat($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_CHAT, '', [], 'Config');

        return $result;
    }

    public static function settingCompanySlogan()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_SLOGAN, '', [], 'Config');
    }

    public static function settingCompanyPrivacy()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_PRIVACY, '', [], 'Config');
    }

    public static function settingCompanyTwitter($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_TWITTER, '', [], 'Config');
        if ($url && !empty($result))
            $result = FHtml::getTwitterLink($result);
        return $result;
    }

    public static function settingCompanyGoogle($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_GOOGLE, '', [], 'Config');
        if ($url && !empty($result))
            $result = FHtml::getGoogleLink($result);
        return $result;
    }

    public static function settingCompanyPhone()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_PHONE, '', [], 'Config');
    }

    public static function settingCompanyAddress()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_ADDRESS, '', [], 'Config');
    }

    public static function settingCompanyEmail($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_EMAIL, '', [], 'Config');
        if (!empty($result) && $url)
            $result = "<a href='mailto:$result' target='_blank'>$result</a>";
        return $result;
    }

    public static function settingCompanyWebsite($url = true)
    {
        $result = FHtml::settingApplication(FHtml::SETTINGS_COMPANY_DOMAIN, '', [], 'Config');
        if (!empty($result) && $url)
            $result = "<a href='$result' target='_blank'>$result</a>";
        return $result;
    }

    public static function settingCompanyDescription()
    {
        return FHtml::settingApplication(FHtml::SETTINGS_COMPANY_DESCRIPTION, '', [], 'Config');
    }

    public static function currentCompanyName()
    {
        return self::settingCompanyName();
    }

    public static function getCurrentFavicon($default = 'favicon.png')
    {
        return $default;
    }

    public static function showCurrentLogo($width = '', $height = '50px', $css = 'logo-default', $default = 'logo.png', $link_url = '')
    {
        $image_file = self::getCurrentLogo();
        if (empty($image_file) || $image_file == $default) {
            $image_file = $default;
            $image_folder = 'www';
        } else {
            $image_folder = 'application';
        }

        $result = FHtml::showImage($image_file, $image_folder, $width, $height, $css, '', true, 'none');
        if (!empty($link_url))
            $result = '<a href="' . $link_url . '">' . $result . '</a>';

        return $result;
    }

    public static function getBannerStyleCSS($model, $module = '')
    {
        $result = FHtml::getBannerImage($model, $module);
        if (!empty($result))
            return "background:url($result) !IMPORTANT;";
        return '';
    }

    public static function getBannerImage($model, $module = '')
    {
        $result = FHtml::getFieldValue($model, 'banner');
        if (!empty($result))
            return FHtml::getImageUrl($result, BaseInflector::camel2id($module));
        return '';
    }

    public static function Session()
    {
        return Yii::$app->session;
    }

    public static function getCachedData($key, $table = '', $column = '')
    {
        $key = self::getCachedKey($key, $table, $column);
        $cache = self::Cache();

        if (isset($cache) && $cache->exists($key)) {
            return $cache->get($key);
        } else
            return null;
    }

    public static function refreshConfig($model)
    {
        if (!isset($model))
            return;

        FHtml::deleteCachedData('application\\' . $model->id);
        FHtml::deleteCachedData('application\\' . $model->code);
        FHtml::saveCachedData($model, 'application\\' . $model->code);
        FHtml::saveCachedData($model, 'application\\' . $model->id);
    }

    public static function deleteCachedData($key, $table = '', $column = '')
    {
        if (!self::isCacheEnabled())
            return null;

        $key = self::getCachedKey($key, $table, $column);
        if (self::Cache()->exists($key)) {
            self::Cache()->delete($key);
        }
    }

    public static function getCachedKey($key, $table = '', $column = '', $id = '')
    {
        if (!empty($key) && $key !== $table && $key !== '@' . $table)
            return $key;

        if (empty($column))
            return $table;
        else
            return $table . '\\' . $column;
    }

    public static function saveCachedData($data, $key, $table = '', $column = '')
    {
        if (!self::isCacheEnabled())
            return null;

        $key = self::getCachedKey($key, $table, $column);
        if (self::Cache()->exists($key)) {
            self::Cache()->delete($key);
        }

        self::Cache()->set($key, $data);
    }

    public static function flushCache()
    {
        if (empty($key))
            self::Cache()->flush();
    }

    //Save Attributes, Files, and Related Objects
    public static function getRequestParam($param, $defaultvalue = null)
    {
        if (is_array($param)) {
            foreach ($param as $param1) {
                if (key_exists($param1, $_REQUEST))
                    return $_REQUEST[$param1];
            }
            return $defaultvalue;
        }

        if (key_exists($param, $_REQUEST))
            $result = $_REQUEST[$param];

        return isset($result) && !empty($result) ? $result : $defaultvalue;
    }

    public static function isUserInApplication($user = null, $application_id = '')
    {
        if (!isset($user))
            $user = FHtml::currentBackendUser();

        if (empty($application_id))
            $application_id = FHtml::currentApplicationCode();

        if (self::isRoleAdmin($user))
            return true;
        else {
            $user_application_id = FHtml::getFieldValue($user, 'application_id');
            return $application_id == $user_application_id;
        }

        return false;
    }

    public static function isRoleAdmin($userid = '')
    {
        if (empty($userid))
            $role = FHtml::getCurrentRole();
        else {
            $role = FHtml::getFieldValue($userid, 'role');
        }

        return $role == \common\models\User::ROLE_ADMIN;
    }

    public static function setApplicationId($id)
    {
        self::Session()->set("application.id", $id);
        $_COOKIE['application.id'] = $id;
        return $id;
    }

    public static function deleteCachedSettings()
    {
        $cachedKey = 'Settings@' . FHtml::currentApplicationId();
        FHtml::deleteCachedData($cachedKey);
    }

    public static function currentApplication($isCached = true)
    {
        $id = FHtml::currentApplicationId();
        $item = self::getCachedData('application\\' . $id);
        if (isset($item) && $isCached) {
            if ($id == $item->code)
                return $item;
        }

        if (is_numeric($id)) {
            $item = Application::findOne($id);
        } else {
            $item = Application::find()->where(['code' => $id])->one();
        }

        if (!isset($item)) {
            $item = new Application();
            $item->code = $id;
            $item->name = DEFAULT_APPLICATION_NAME;
            $item->is_active = 1;
            $item->save();
        }

        if (isset($item))
            self::saveCachedData($item, 'application\\' . $id);

        return $item;
    }

    public static function Helper()
    {
        $helper = \Yii::$app->helper;

        if (!isset($helper))
            return $helper;
        else
            return new Helper();
    }

    public static function currentUsername()
    {
        $identity = self::currentUserIdentity();
        if (isset($identity))
            return self::currentUserIdentity()->username;
        else
            return '';
    }

    public static function currentUserAvatar()
    {
        $user = self::currentBackendUser();
        return FHtml::getFieldValue($user, ['image', 'avatar']);
    }

    public static function currentCategoryId()
    {
        $category_id = FHtml::getRequestParam(['category_id', 'categoryid']);
        return $category_id;
    }

    public static function currentCategory($category_id = '')
    {
        if (empty($category_id))
            $category_id = self::currentCategoryId();
        $category = null;
        if (!empty($category_id)) {
            $category = \backend\models\ObjectCategory::findOne($category_id);
        }

        return $category;
    }

    public static function getExcludedSettings()
    {
        return FHtml::SETTINGS_EXCLUDED;
    }

    public static function setting($category, $default_value = '', $params = [], $group = 'Config', $editor = '', $lookup = '', $override_if_empty = false)
    {
        if (empty($group))
            $group = 'Config';

        if (!FHtml::isDBSettingsEnabled() || FHtml::isInArray($category, self::getExcludedSettings()))
            return $default_value;

        $result = self::getApplicationConfig($category, $default_value, false);

        if (isset($result) && !empty($result))
            return $result;

        $zone = ucfirst(FHtml::currentZone());
        $module = BaseInflector::camel2words(FHtml::currentModule());
        if (empty($module))
            $module = 'System';

        if (empty($group)) {

            $group = $module;
            $category = $zone . '/' . ucfirst(FHtml::currentController()) . '/ ' . ucfirst(FHtml::currentAction()) . ': ' . ucfirst($category);
        }

        return self::config($category, $default_value, $params, $group, $editor, $lookup, $override_if_empty);
    }

    public static function settingPage($category, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '', $override_if_empty = false)
    {
        $page_code = self::currentPageCode();

        $page_model =  CmsPage::findOne(['code' => $page_code]); //FHtml::getModel('cms_page', '', ['code' => $code], '', true);
        $method_name = str_replace(' ', '_', strtolower($category));
        if (isset($page_model)) {
            if (FHtml::field_exists($page_model, $method_name)) {
                $value = FHtml::getFieldValue($page_model, $method_name);
                return $value;
            }
        } else {
            $page_model = new CmsPage();
            $page_model->code = $page_code;
            $page_model->application_id = FHtml::currentApplicationCode();
            $page_model->name = FHtml::currentController();
            $page_model->created_date = FHtml::Today();
            $page_model->is_active = 1;
            $page_model->created_user = FHtml::currentUserId();
            FHtml::setFieldValue($page_model, $method_name, $default_value);
            $page_model->save();
            if (FHtml::field_exists($page_model, $method_name)) {
                return $default_value;
            }
        }

        if (!FHtml::isDBSettingsEnabled() || FHtml::isInArray($category, self::getExcludedSettings()))
            return $default_value;

        $category = $page_code . '?' . ucfirst($category);

        return self::config($category, $default_value, $params, $group, $editor, $lookup, $override_if_empty);
    }

    public static function settingPageStyleSheet($style = '') {
        $result = self::settingPage('page_style', $style, [], 'Style', FHtml::EDITOR_TEXT);

        if (!isset($result) || empty($result))
            $result = $style;
        if (!empty($result) && !StringHelper::startsWith($result, '<style'))
            return "<style>\n$result\n</style>";
        else
            return $result;
    }

    public static function settingPageScript($script = '') {
        $result = self::settingPage('page_script', $script, [], 'Style', FHtml::EDITOR_TEXT);

        if (!isset($result) || empty($result))
            $result = $script;
        if (!empty($result) && !StringHelper::startsWith($result, '<script'))
            return "<script>\n$result\n</script>";
        else
            return $result;
    }

    public static function settingPageWidth($width = '') {
        $result = self::settingPage('page_width', $width, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $width;
    }

    public static function settingPageTitle($title = '') {
        $result = self::settingPage('page_title', $title, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $title;
    }

    public static function settingPageKeywords($keywords = '') {
        $result = self::settingPage('keywords', $keywords, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $keywords;
    }

    public static function settingPageDescription($description = '') {
        $result = self::settingPage('description', $description, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $description;
    }

    public static function settingPageImage($description = '') {
        $result = self::settingPage('image', $description, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            $result = $result;
        else
            $result = $description;

        if (!empty($result))
            return FHtml::getImageUrl($result, 'cms-page');
        else
            return FHtml::getCurrentLogo();
    }

    public static function settingPageBodyCSS($style = '') {
        $result = self::settingPage(FHtml::SETTINGS_BODY_CSS, $style, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $style;
    }

    public static function settingPageBodyStyle($style = '') {
        $result = self::settingPage('body_style', $style, [], 'Style', FHtml::EDITOR_TEXT);

        if (isset($result) && !empty($result))
            return $result;

        return $style;
    }

    public static function settingModule($category, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '', $override_if_empty = false)
    {
        if (!FHtml::isDBSettingsEnabled() || FHtml::isInArray($category, self::getExcludedSettings()))
            return $default_value;

        $zone = ucfirst(FHtml::currentZone());
        $module = BaseInflector::camel2words(FHtml::currentModule());
        if (empty($module))
            $module = 'System';

        if (empty($group))
            $group = $module;

        $category = $module . '/ ' . ucfirst($category);

        return self::config($category, $default_value, $params, $group, $editor, $lookup, $override_if_empty);
    }

    public static function settingApplication($category, $default_value = '', $params = [], $group = 'Config', $editor = '', $lookup = '', $override_if_empty = false)
    {
        return self::setting($category, $default_value, $params, $group);
    }

    public static function settingApplicationDefaultModule($default_value = '')
    {
        return self::settingApplication('menu_group', $default_value);
    }

    public static function settingApplicationDatabase($default_value = 'db')
    {
        return self::settingApplication('database', $default_value);
    }

    public static function settingApplicationLang($default_value = DEFAULT_LANG)
    {
        return self::settingApplication('lang', $default_value);
    }

    public static function settingWebsite($category, $default_value = '')
    {

        return self::settingApplication($category, $default_value, [], 'Website');
    }

    public static function settingWebsiteHeaderView($default_value = '')
    {
        return self::settingApplication('header_view', $default_value, [], 'Website');
    }

    public static function settingWebsiteFooterView($default_value = '')
    {
        return self::settingApplication('footer_view', $default_value, [], 'Website');
    }

    public static function settingWebsitePageHeader($default_value = '')
    {
        return self::settingApplication('header_content', $default_value, [], 'Website');
    }

    public static function settingWebsiteBodyCSS($default_value = '')
    {
        return self::settingApplication('body_css', $default_value, [], 'Website');
    }

    public static function settingWebsiteBackground($default_value = '')
    {
        return self::settingApplication('background', $default_value, [], 'Website');
    }

    public static function settingWebsiteName($default_value = '')
    {
        return self::settingApplication('name', $default_value, [], 'Website');
    }

    public static function settingWebsiteKeyWords($default_value = '')
    {
        return self::settingApplication('keywords', $default_value, [], 'Website');
    }

    public static function settingWebsiteDescription($default_value = '')
    {
        return self::settingApplication('description', $default_value, [], 'Website');
    }

    public static function settingWebsiteBodyStyle($default_value = '')
    {
        return self::settingApplication('body_style', $default_value, [], 'Website');
    }

    public static function settingWebsitePageFooter($default_value = '')
    {
        return self::settingApplication('footer_content', $default_value, [], 'Website');
    }

    public static function settingWebsitePageCSS($default_value = '')
    {
        return self::settingApplication('page_css', $default_value, [], 'Website');
    }

    public static function settingWebsitePageStyle($default_value = '')
    {
        $result = self::settingApplication('page_style', $default_value, [], 'Website');
        if (!empty($result) && !StringHelper::startsWith($result, '<style'))
            return "<style>\n$result\n</style>";
        else
            return $result;
    }

    public static function settingConfig($category, $default_value = '')
    {
        return self::settingApplication($category, $default_value, [], 'Config');
    }

    public static function settingDynamicGrid()
    {
        return self::isDynamicFormEnabled() && FHtml::settingApplication('dynamic_grid', false, [], '', FConfig::EDITOR_BOOLEAN);
    }

    public static function settingDynamicForm()
    {
        return self::isDynamicFormEnabled() && FHtml::settingApplication('dynamic_form', false, [], '', FConfig::EDITOR_BOOLEAN);
    }

    public static function settingPageView($default_value = '', $action = '', $params = [], $group = '', $editor = '', $lookup = '')
    {
        $action = empty($action) ? FHtml::currentAction() : $action;
        $category = BaseInflector::camel2words($action);
        if (FHtml::isInArray($action, FHtml::EXCLUDED_ACTIONS_AS_PAGEVIEW_SETTINGS))
            return $default_value;

        return self::settingPage($category, $default_value, $params, $group, $editor, $lookup);
    }

    public static function settingPageObject($default_value = '', $params = [], $group = '', $editor = '', $lookup = '')
    {
        return $default_value;
        //return self::settingPage('Page Object', $default_value, $params, $group, $editor, $lookup);
    }

    public static function article($category, $default_value = '', $params = [], $group = '', $editor = '', $lookup = '')
    {
        return self::config($category, $default_value, $params, $group, $editor, $lookup);
    }

    public static function content($content = '', $model = null, $field = 'overview', $object_type = 'cms_article')
    {
        if (is_string($model)) // search $model by code
        {
            $data = FHtml::getModel($object_type, '', ['code' => $model], null, false);
            if (!isset($data))
                return FHtml::getFieldValue($data, $field, $content);
            else {
                $data = FHtml::createModel($object_type);
                FHtml::setFieldValue($data, $field, $content);
                return $content;
            }
        } else { // return

        }
    }

    public static function getApplicationConfig($category, $default_value = null, $checkHelperOnly = true) {
        $method_name1 = str_replace(' ', '', $category);
        $method_name2 = str_replace('_', '', $method_name1);

        $method_names = [$method_name1, $method_name2];

        if (FHtml::isApplicationsEnabled()) {

            // 2. If not, get from current Application (in Application table)
            $config = FHtml::getApplicationHelper();
            foreach ($method_names as $method_name) {
                if (isset($config) && method_exists($config, $method_name)) {
                    $result = $config::$method_name();
                    if (empty($result))
                        return null;
                    return $result;
                }

                if (isset($config) && property_exists($config, $method_name)) {
                    $result = $config::$method_name;
                    if (empty($result))
                        return null;
                    return $result;
                }
            }

            if (!$checkHelperOnly) {
                // Always loop when call: Application::findOne()
                $config = self::currentApplication();
                if (isset($config) && !empty($config)) {
                    if (FModel::field_exists($config, $category)) {
                        $result = $config[$category];
                        if (isset($result) && !empty($result))
                            return $result;
                        else if (!empty($default_value)) {
                            FHtml::setFieldValue($config, $category, $default_value);
                            $config->save();
                        }
                        return $default_value;
                    }
                }
            }
        }

        return $default_value;
    }

    public static function currentApplicationFolder() {

        $result = self::getApplicationConfig('folder');
        if (!empty($result))
            return $result;

        return FHtml::currentApplicationId();
    }

    public static function currentApplicationDatabase() {

        $result = self::getApplicationConfig('database');
        if (!empty($result))
            return $result;

        return FHtml::currentApplicationId();
    }

    public static function currentApplicationCode() {

        $result = self::getApplicationConfig('code');
        if (!empty($result))
            return $result;

        return FHtml::currentApplicationId();
    }


    public static function config($category, $default_value = '', $params = [], $group = 'Config', $editor = '', $lookup = '', $override_if_empty = false)
    {
        if (empty($group))
            $group = 'Config';

        // Default values, no need to lookup at database
        if (!FHtml::isDBSettingsEnabled() || FHtml::isInArray($category, self::getExcludedSettings()))
            return $default_value;

        if (empty($editor))
            $editor = 'textarea';

        // 1. Get from Request (Get, Post) param first
        if (!empty($category)) {
            $items = explode('.', $category);
            $param = end($items);
            if (isset($_REQUEST[$param]))
                return $_REQUEST[$param];
        }

        if (FHtml::isDBSettingsEnabled()) {
            // 3. If not, get from Config table
            $cachedKey = 'Settings@' . FHtml::currentApplicationId();
            $settings = FHtml::getCachedData($cachedKey);

            if (!isset($settings)) {
                $settingsModel = Settings::findAll([]);
                $settings = [];
                if (isset($settingsModel) && !empty($settingsModel)) {
                    foreach ($settingsModel as $model) {
                        $settings = ArrayHelper::merge($settings, [$model->metaKey => $model->metaValue]);
                    }
                }

                FHtml::saveCachedData($settings, $cachedKey);
            }

            if (key_exists($category, $settings)) {
                return $settings[$category];
            } else {
                // Not yet existed in Settings DB, save it into next time
                $model = Settings::findOne(['metaKey' => $category]);

                if (isset($model)) {
                    if (!empty($model->metaValue))
                        return $model->metaValue;
                    else {
                        if ($override_if_empty) {
                            $model->metaValue = $default_value;
                            $model->save();
                        }
                    }
                } else {
                    $model = new Settings();
                    $model->metaKey = $category;
                    $model->metaValue = (!is_array($default_value) || !is_object($default_value)) ? $default_value : '';

                    if (isset($default_value) && (is_bool($default_value) || $default_value === 1 || $default_value === 0)) {
                        $model->editor = FHtml::EDITOR_BOOLEAN;
                    } else if (is_numeric($default_value)) {
                        $model->editor = FHtml::EDITOR_NUMERIC;
                    }

                    if (!empty($params)) {
                        $model->editor = FHtml::EDITOR_SELECT;
                        $model->lookup = FHtml::encode($params);
                    }

                    if (!empty($editor))
                        $model->editor = $editor;

                    if (!empty($lookup))
                        $model->lookup = $lookup;

                    if ($category == FHtml::SETTINGS_FIELD_LAYOUT) {
                        $model->editor = FHtml::EDITOR_SELECT;
                        $group = 'Theme';
                        $model->lookup = 'field_layout';
                    }

                    if (empty($lookup) && $model->editor == FHtml::EDITOR_SELECT)
                        $model->lookup = str_replace(' ', '_', strtolower($category));

                    if (strpos($category, 'Format') !== false)
                        $group = 'Format';

                    if (empty($group))
                        $group = 'Others';

                    if (!empty($group))
                        FHtml::setFieldValue($model, 'group', $group);

                    $model->application_id = FHtml::currentApplicationCode();
                    $model->is_system = 0;
                    $model->save();
                    FHtml::deleteCachedSettings();
                }
            }
        }

        //To be done: check if category setting already existed in Configuration table, if not then return $default_value
        return $default_value;
    }

    public static function applicationLangsArray() {
        return FHtml::decode(self::getApplicationConfig('Languages', FHtml::encode(self::ARRAY_LANG), false));
    }

    public static function t($category, $message = false, $params = [], $language = null)
    {
        $isLangEnabled = self::isLanguagesEnabled();

        try {
            if ($message === false) {
                $message = $category;
                $category = 'common';
            }

            if (is_integer($category) || !is_string($category) || !$isLangEnabled)
                return $message;

            if (empty($language))
                $language = FHtml::currentLang();

            //$message = trim($message, ".;,! ");
            foreach (FHtml::MULTILANG_TEXT_REMOVALS as $item) {
                $message = str_replace($item, '', $message); //tricky

            }

            $not_existed = false;

            $result = '';
            //$key = FHtml::getCachedKey('Settings_Text');
            //$textArray = self::getCachedText(); // Remove this in order to Improve performance
            $textArray = [];

//            $result = self::getTextFromSettingsText($message, $language, $textArray, $key, $category);
//            if (!empty($result))
//                return $result;

            $result = Yii::t($category, $message, $params, $language);

            if (!empty($result) && $result != $message)
                return $result;


            $result = Yii::t('common', $message, $params, $language);

            //To be done: check if message already in db, if not then save to db. Otherwise, return default Yii:t
        } catch (Exception $e) { // File does not existed
            $result = Yii::t('common', $message, $params, $language);
        }

        return $result;
    }

    public static function getTextFromSettingsText($message, $language = '', $textArray = null, $cachedKey = '', $category = '')
    {
        if (isset($textArray) && !empty($textArray)) {
            $text_key = str_replace(' ', '_', strtolower($message));

            if (key_exists($text_key, $textArray)) {
                $textItem = $textArray[$text_key];
                $field = 'description' . ((empty($language) || $language == DEFAULT_LANG) ? '' : ('_' . $language));
                if (key_exists($field, $textItem)) {
                    if (!empty($textItem[$field]))
                        return $textItem[$field];
                }

            } else {
                $model = FHtml::createModel('settings_text');
                if (isset($model) && is_string($text_key) && !is_numeric($text_key)) {
                    FHtml::setFieldValue($model, 'group', FHtml::currentController());
                    $model->name = $text_key;
                    $model->description = $message;
                    $model->description_en = $message;
                    $model->save();
                }
            }
        } else {
            $text_key = str_replace(' ', '_', strtolower($message));

            $model = FHtml::getModel('settings_text', '', ['name' => $text_key], null, false);
            if (isset($model)) {
                $field = 'description' . (empty($language) ? '' : ('_' . $language));
                if (FHtml::field_exists($model, $field))
                    return FHtml::getFieldValue($model, $field);
            } else {
                $model = FHtml::createModel('settings_text');
                FHtml::setFieldValue($model, 'group', FHtml::currentController());
                $model->name = $text_key;
                $model->description = $message;
                $model->description_en = $message;
                $model->save();
            }
        }

        return '';
    }

    public static function settingDateFormat()
    {
        return self::settingApplication(FHtml::SETTINGS_DATE_FORMAT, 'd.m.Y', [], 'Format');
    }

    public static function settingDateTimeFormat()
    {
        return self::settingApplication(FHtml::SETTINGS_DATETIME_FORMAT, 'd.m.Y hh:ii', [], 'Format');
    }

    public static function settingTimeFormat()
    {
        return self::settingApplication(FHtml::SETTINGS_TIME_FORMAT, 'hh:ii', [], 'Format');
    }

    public static function settingDecimalSeparatorFormat()
    {
        return self::settingApplication('Decimal Symbol', '.', [], 'Format');
    }

    public static function settingThousandSeparatorFormat()
    {
        return self::settingApplication('Thousand Grouping Symbol', ',', [], 'Format');
    }

    public static function settingNullDisplayFormat()
    {
        return self::settingApplication('Null Display (empty value)', '...', [], 'Format');
    }

    public static function settingDigitsAfterDecimalFormat()
    {
        return self::settingApplication('Digit After Decimal', 0, [], 'Format');
    }

    public static function settingCurrency()
    {
        return self::settingApplication(FHtml::SETTINGS_CURRENCY, 'USD', FHtml::getComboArray('currency'), 'Format');
    }

    public static function settingMaxFileSize()
    {

        return self::settingApplication('Max File Size', 900000, [], 'Format');
    }

    public static function settingAcceptedFileType()
    {
        return self::settingApplication('Accepted Files uploaded', 'image/*,video/*,audio/*,.docx,.txt,.xls,.pdf,.xlsx,.doc,.ppt', [], 'Format');
    }
}