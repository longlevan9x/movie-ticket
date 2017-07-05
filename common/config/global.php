<?php

// SYSTEM CONFIGURATION
define('APPLICATIONS_ENABLED', false); // Multiple Applications or not
define('DEFAULT_APPLICATION_ID', 'mozamovieticket'); // Default Application ID, should not change this value

// SETTINGS
define('CACHE_ENABLED', true);
define('LANGUAGES_ENABLED', true); // Multiple Languages or not
define('DB_LANGUAGES_ENABLED', true); // Multiple Languages or not
define('DEFAULT_LANG', 'en');

define('ROOT_FOLDER', '');
define('DEFAULT_PASSWORD', '123456');

// PAYPAL
define('PAYPAL_API_USERNAME',"hung.hoxuan_api1.gmail.com");
define('PAYPAL_API_EMAIL',"hung.hoxuan@gmail.com");

define('PAYPAL_API_PASSWORD',"UBVDUPTMCEGA6Y88");
define('PAYPAL_API_SIGNATURE',"AFcWxV21C7fd0v3bYYYRCpSSRl31AlKUjYQEm9StBtsQZbeWCwCJcHxC");
define('PAYPAL_API_LIVE', true);

// FOOTPRINT/ HASH CHECKOUT
define('SECRET_KEY', 'mozagroup2017'); // used in SHCODE
define('FOOTPRINT_TIME_LIMIT', 30);
define('SERVER_TIME_ZONE', '');

// Advanced Configuration: DYNAMIC FORMS etc. Only used for Super Administrators. Set all to FALSE to gain max performance !
define('SYSTEM_ADMIN_ENABLED', false); // Allow admin to edit objects, schema, views etc or not
define('DYNAMIC_FORM_ENABLED', false); // Build form dynamically
define('REQUIRED_INDEX_PHP', true); // Auto add index.php in Url
define('DB_SETTINGS_ENABLED', true); // Get Setting from Database
define('FRONTEND_MENU_FROM_MODULE', false); // true, false or Module name or Application name (string)
define('FRONTEND_MENU_FROM_DB', false);
define('DYNAMIC_OBJECT_ENABLED', false); // Build object dynamically
define('OBJECT_ACTIONS_ENABLED', false); // Build object dynamically

define('DEFAULT_APPLICATION_NAME', 'MOZA Business Solutions');
define('WELCOME_MESSAGE', 'You are using product of MOZA Group. For more products and support, please visit: <a target="_blank" href="http://www.mozasolution.com"/>www.mozasolution.com</a>' );
define('DEFAULT_APPLICATION_WEBSITE', 'http://mozasolution.com');
define('DEFAULT_APPLICATION_VERSION', '1.0');
define('DEFAULT_APPLICATION_THEME', 'unify');
define('DEFAULT_ADMIN_MODULES', 'Cms,Ecommerce,App,System');

// DIRECTORIES
define('DS', DIRECTORY_SEPARATOR);
define('SITE', 'site');
define('IMAGES_DIR', 'images');
define('WEB_DIR', 'web');
define('ADMIN_DASHBOARD_MODULE', '');
define('PRODUCTS_DIR', 'product');
define('CATEGORY_DIR', 'object-category');
define('SALEOFF_DIR', 'saleoff');
define('NEWS_DIR', 'cms-blogs');
define('ABOUTUS_DIR', 'cms-about');
define('EMPLOYEE_DIR', 'cms-employee');

defined('UPLOAD_DIR') or define('UPLOAD_DIR', 'upload');
define('WWW_DIR', 'www');
define('APP_USER_DIR', 'app-user');
define('PACKAGE_DIR', 'package');
define('VERIFICATION_DIR', 'verification');
define('PRODUCT_DIR', 'product');
define('ICON_DIR', 'icon');
define('ARTICLE_DIR', 'cms-article');
define('SLIDER_DIR', 'cms-slide');
define('GALLERY_DIR', 'gallery');
define('HOSTAGENT_DIR', 'hostagent');
define('PEM_DIR', 'pem');
define('TRANSPORT_DRIVER_DIR', 'transport-driver');
define('TRANSPORT_VEHICLE_DIR', 'transport-vehicle');
define('PRODUCT_DEAL_DIR', 'product-deal');

//DEFAULT VALUES
define('DEFAULT_IMAGE', 'no_image.jpg');
defined('FRONTEND') or define('FRONTEND', 'frontend');
defined('BACKEND') or define('BACKEND', 'backend');

if (!isset($root_dir)) $root_dir = dirname(dirname(dirname(__FILE__)));
Yii::setAlias('@' . IMAGES_DIR, $root_dir . DS . BACKEND . DS . WEB_DIR . DS . IMAGES_DIR);
Yii::setAlias('@' . UPLOAD_DIR, $root_dir . DS . BACKEND . DS . WEB_DIR . DS . UPLOAD_DIR);
Yii::setAlias('@' . SITE, $root_dir);

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@applications', dirname(dirname(__DIR__)) . '/applications');

class Globals extends \common\components\FHtml
{

}