<?php

namespace common\components;

use common\components\FError;
use Yii;
use common\components\FHtml;

class FConstant extends FError
{
    const USER_STATUS_DELETED = 0;
    const USER_STATUS_ACTIVE = 10;
    const ROLE_USER = 10;
    const ROLE_MODERATOR = 20;
    const ROLE_ADMIN = 30;
    const ROLE_ALL = '@';
    const ROLE_NONE = '?';
    const ROLE_CODE_GROUPS = ['admin' => FHtml::ROLE_ADMIN, 'user' => FHtml::ROLE_USER, 'moderator' => FHtml::ROLE_MODERATOR, 'everybody' => FHtml::ROLE_ALL, 'nobody' => FHtml::ROLE_NONE ];

    const APPLICATION_NONE = '';
    const APPLICATION_MANY = true;

    const LANGUAGES_NONE = '';
    const LANGUAGES_LABELS_ONLY = true;
    const LANGUAGES_PARAM = 'language';

    const LANGUAGES_LABELS_AND_DB = 'all';

    const EXCLUDED_TABLES_AS_APPLICATIONS = ['user'];
    const EXCLUDED_TABLES_AS_MULTILANGS = ['user*', 'object*', 'app_*', 'auth*', 'setting*'];
    const INCLUDED_TABLES_AS_MULTILANGS = ['object_setting', 'object_category'];
    const EXCLUDED_TABLES_AS_OBJECT_CHANGES = ['object*', 'app_*', 'auth*', 'setting*'];
    const EXCLUDED_ACTIONS_AS_PAGEVIEW_SETTINGS = ['create', 'update', 'view', 'index', 'Form', 'Detail'];

//    Auth
    const STATE_ACTIVE = 1;

    const
        CHANGE_TYPE = 'change',
        CLEAR_TYPE = 'clear',
        FILL_TYPE = 'fill';

    //HungHX: 20160801
    const
        NULL_VALUE = '...';

    //2017/3/21
    const
        STATUS_ACTIVE = '1',
        STATUS_INACTIVE = '0',
        STATUS_NEW = 'new',
        STATUS_PLANNING = 'planning',
        STATUS_PROCESSING = 'processing',
        STATUS_SHIPPING = 'shipping',
        STATUS_DONE = 'done',
        STATUS_FAIL = 'fail',
        STATUS_HALF = 'half',
        STATUS_PENDING = 'pending',
        STATUS_REJECTED = 'rejected',
        STATUS_ON_HOLD = 'on-hold',
         STATUS_ARRAY = [FHtml::STATUS_NEW, FHtml::STATUS_PROCESSING, FHtml::STATUS_DONE, FHtml::STATUS_PENDING, FHtml::STATUS_FAIL];

    const
        CODE_NEWLINE = "
        ",
        CODE_TAB = '    ',
        CODE_TAB2 = '       ',
        CODE_TAB3 = '           ';

    const
        TYPE_ANDROID = 1,
        TYPE_IOS = 2,
        NO_IMAGE = 'no_image.jpg';
    const
        DEFAULT_ITEMS_PER_PAGE = 50;
    const
        WIDGET_COLOR_DEFAULT = "light",
        WIDGET_TYPE_DEFAULT = "light bordered",
        WIDGET_TYPE_BOX = "box",
        WIDGET_TYPE_NONE = "light", // light, light bordered, box
        WIDGET_TYPE_LIGHT = "light bordered";

    const RENDER_TYPE_CODE = 'code';
    const RENDER_TYPE_AUTO = 'auto';
    const RENDER_TYPE_DB_SETTING = 'database';

    const VIEWS_PRINT_HEADER = '../../../../views/www/_print_header';
    const VIEWS_PRINT_HEADER1 = '../../../../views/www/_print_header';

    const MULTILANG_TEXT_REMOVALS = [' ID Array', ' Id Array', 'Object ', 'Count ', ' Id', ' ID', ' id'];

    const
        ADMIN_EMAIL = 'ADMIN_EMAIL',
        GOOGLE_API_KEY = 'GOOGLE_API_KEY',
        PEM_FILE = 'PEM_FILE',
        PRIVACY = 'PRIVACY',
        COMPANY_NAME = 'COMPANY_NAME',
        COMPANY_DESCRIPTION = 'COMPANY_DESCRIPTION',
        COMPANY_HOMEPAGE = 'COMPANY_HOMEPAGE',
        SUCCESS = 'SUCCESS',
        ERROR = 'ERROR';

    const
        LABEL_ACTIVE = 'active',
        LABEL_INACTIVE = 'inactive',
        LABEL_NEW = 'new',
        LABEL_NORMAL = 'normal',
        LABEL_PREMIUM = 'premium',
        LABEL_OLD = 'old';

    const
        ACTION_DELETE = 'delete',
        ACTION_EDIT = 'update',
        ACTION_ADD = 'add',
        ACTION_CREATE = 'create',
        ACTION_REJECT = 'reject',
        ACTION_APPROVED = 'approve',
        ACTION_VIEW = 'view',
        ACTION_SAVE = 'save',
        ACTION_LOAD = 'load',
        ACTION_SEND = 'send';

    const
        TABLE_USER = 'user',
        TABLE_SETTINGS = 'settings',
        TABLE_CATEGORIES = 'object_category',
        TABLE_PRODUCT = 'product',
        TABLE_ARTICLE = 'cms_article',
        TABLE_PROMOTION = 'cms_promotion',
        TABLE_PORTFOLIO = 'cms_portfolio',
        TABLE_BLOGS = 'cms_blogs',
        TABLE_NEWS = 'cms_blogs',
        TABLE_ABOUT = 'cms_about',
        TABLE_TESTIMONIAL = 'cms_testimonial',
        TABLE_EMPLOYEE = 'cms_employee',
        TABLE_OBJECT_TYPE = 'object_type',
        TABLE_SERVICE = 'cms_service',
        TABLE_FAQ = 'cms_faq',
        TABLE_CONTACT = 'cms_contact',
        TABLE_OBJECT_FILES = 'object_file',
        TABLE_OBJECT_ATTRIBUTES = 'object_attributes',
        TABLE_OBJECT_META = 'object_attributes',
        OBJECT_TYPE_DEFAULT = 'common',
        TABLE_OBJECT_SETTING = 'object_setting';

    const EDIT_TYPE_INLINE = 'inline',
        EDIT_TYPE_POPUP = 'popup',
        EDIT_TYPE_VIEW = 'view',
        EDIT_TYPE_DEFAULT = 'default',
        EDIT_TYPE_INPUT = 'input';

    const DISPLAY_TYPE_DEFAULT = 'list';
    const DISPLAY_TYPE_TABLE = 'table';
    const DISPLAY_TYPE_LIST = 'list';
    const DISPLAY_TYPE_GRID_SMALL = 'grid2';
    const DISPLAY_TYPE_GRID_BIG = 'grid3';
    const DISPLAY_TYPE_GRID = 'grid';
    const DISPLAY_TYPE_PRINT = 'print';
    const DISPLAY_TYPE_IMAGE = 'image';
    const DISPLAY_TYPE_WIDGET = 'widget';

    const COLUMNS_HIDDEN = array(
        'password*',
        'auth_*',
        'application_id',
        'sort_order',
        'thumbnail',
        '*content',
        '*description',
        '*tags',
        '*overview',
        '*created',
        '*modified',
        '*user',
        'create_date', 'created_*', 'modified_*', 'modified_date'
    );

    const COLUMNS_FORM_HIDDEN = array(
        'password*',
        'auth_*',
        'application_id',
        'sort_order'
    );

    const COLUMNS_FORM_READONLY = array(
        'count*',
        'rate*',
        'created_date',
        'created_user',
        'modified_date',
        'modified_user',

        );

    const COLUMNS_VISIBLE = array(
        'code',
        'name',
        'title',
        'image',
        'icon',
        'color',
        'lang',
        'phone',
        'address',
        'type',
        'status',
        'price',
        'is_active', 'is_*',
        'is_top',
        'category_id'
    );

    const COLUMNS_SYSTEMS = array(
        'application_id'
    );

    const
        ARRAY_CONTROLS_ALIGNMENT = [
        ['id' => 'horizontal', 'name' => 'Horizontal'],
        ['id' => 'vertical', 'name' => 'Vertical'],
    ],
        ARRAY_BUTTONS_STYLE = [
        ['id' => 'icons', 'name' => 'Icons'],
        ['id' => 'dropdown', 'name' => 'Dropdown'],
    ],
        ARRAY_ALIGNMENT = [
        ['id' => 'left', 'name' => 'Left'],
        ['id' => 'right', 'name' => 'Right'],
        ['id' => 'center', 'name' => 'Center']],
        ARRAY_TRANSITION_SPEED = [
        ['id' => '500', 'name' => 'Super fast'],
        ['id' => '1000', 'name' => 'Fast'],
        ['id' => '1500', 'name' => 'Normal'],
        ['id' => '2000', 'name' => 'Slow'],
        ['id' => '2500', 'name' => 'Very slow']],
        ARRAY_ADMIN_THEME = [
        ['id' => 'default', 'name' => 'Default'],
        ['id' => 'darkblue', 'name' => 'Dark Blue'],
        ['id' => 'light', 'name' => 'Light'],
        ['id' => 'light2', 'name' => 'Light 2'],
        ['id' => 'blue', 'name' => 'Blue'],
        ['id' => 'gray', 'name' => 'Gray']],
        ARRAY_THEME_STYLE = [
        ['id' => 'Material Design', 'name' => 'Material Design'],
        ['id' => 'Bootstrap', 'name' => 'Bootstrap'],
    ],
        ARRAY_GRID_BUTTONS = [
        ['id' => 'icons', 'name' => 'icons'],
        ['id' => 'dropdown', 'name' => 'dropdown']],

        ARRAY_TRANSITION_TYPE = [
        ['id' => 'fade', 'name' => 'fade'],
        ['id' => 'zoomout', 'name' => 'zoomout'],
        ['id' => '3dcurtain-vertical', 'name' => '3dcurtain-vertical'],
        ['id' => 'random', 'name' => 'random']],

        ARRAY_FIELD_LAYOUT = [
        ['id' => self::LAYOUT_ONELINE, 'name' => self::LAYOUT_ONELINE],
        ['id' => self::LAYOUT_NEWLINE, 'name' => self::LAYOUT_NEWLINE],
        ['id' => self::LAYOUT_ONELINE_RIGHT, 'name' => self::LAYOUT_ONELINE_RIGHT],
        ['id' => self::LAYOUT_TEXT, 'name' => self::LAYOUT_TEXT],
        ['id' => self::LAYOUT_NO_LABEL, 'name' => self::LAYOUT_NO_LABEL],
        ['id' => self::LAYOUT_TABLE, 'name' => self::LAYOUT_TABLE],
    ],
        ARRAY_EDITOR = [
        ['id' => 'text', 'name' => 'Textarea'],
        ['id' => 'date', 'name' => 'Date'],
        ['id' => 'datetime', 'name' => 'DateTime'],
        ['id' => 'time', 'name' => 'Time'],
        ['id' => 'html', 'name' => 'Html'],
        ['id' => 'numeric', 'name' => 'Numeric'],
        ['id' => 'checkbox', 'name' => 'Boolean'],
        ['id' => 'file', 'name' => 'File'],
        ['id' => 'image', 'name' => 'Image'],
        ['id' => 'select', 'name' => 'Select'],
        ['id' => 'selectmany', 'name' => 'Select Array'],
        ['id' => 'rate', 'name' => 'Rate'],
        ['id' => 'slide', 'name' => 'Slide'],
    ],
        ARRAY_COLOR = [
        ['id' => 'default', 'name' => 'Grey'],
        ['id' => 'success', 'name' => 'Green'],
        ['id' => 'primary', 'name' => 'Blue'],
        ['id' => 'warning', 'name' => 'Yellow'],
        ['id' => 'danger', 'name' => 'Red']],
        ARRAY_GENDER = [
        ['id' => 'Male', 'name' => 'Male'],
        ['id' => 'Female', 'name' => 'Female'],
        ['id' => 'Kid', 'name' => 'Kid'],
        ['id' => 'N/A', 'name' => 'Dont know'],
        ['id' => 'Others', 'name' => 'Others']],
        ARRAY_DBTYPE = [
        ['id' => 'pk', 'name' => 'pk'],
        ['id' => 'upk', 'name' => 'upk'],
        ['id' => 'bigpk', 'name' => 'bigpk'],
        ['id' => 'ubigpk', 'name' => 'ubigpk'],
        ['id' => 'char', 'name' => 'char'],
        ['id' => 'string', 'name' => 'string'],
        ['id' => 'text', 'name' => 'text'],
        ['id' => 'smallint', 'name' => 'smallint'],
        ['id' => 'integer', 'name' => 'integer'],
        ['id' => 'bigint', 'name' => 'bigint'],
        ['id' => 'float', 'name' => 'float'],
        ['id' => 'double', 'name' => 'double'],
        ['id' => 'decimal', 'name' => 'decimal'],
        ['id' => 'datetime', 'name' => 'datetime'],
        ['id' => 'timestamp', 'name' => 'timestamp'],
        ['id' => 'time', 'name' => 'time'],
        ['id' => 'date', 'name' => 'date'],
        ['id' => 'binary', 'name' => 'binary'],
        ['id' => 'boolean', 'name' => 'boolean'],
        ['id' => 'money', 'name' => 'money'],
    ];

    const
        RELATION_FOREIGN_KEY = '_',
        RELATION_ONE_MANY = '1',
        RELATION_MANY_MANY = '';

    /**
     * Bootstrap Contextual Color Types
     */
    const TYPE_DEFAULT = 'default'; // only applicable for panel contextual style
    const TYPE_PRIMARY = 'primary';
    const TYPE_INFO = 'info';
    const TYPE_DANGER = 'danger';
    const TYPE_WARNING = 'warning';
    const TYPE_SUCCESS = 'success';
    const TYPE_ACTIVE = 'active'; // only applicable for table row contextual style

    /**
     * Boolean Icons
     */
    const ICON_ACTIVE = '<span class="glyphicon glyphicon-ok text-success"></span>';
    const ICON_INACTIVE = '<span class="glyphicon glyphicon-remove text-danger"></span>';

    /**
     * Expand Row Icons
     */
    const ICON_EXPAND = '<span class="glyphicon glyphicon-expand"></span>';
    const ICON_COLLAPSE = '<span class="glyphicon glyphicon-collapse-down"></span>';
    const ICON_UNCHECKED = '<span class="glyphicon glyphicon-unchecked"></span>';
    const ICON_ARRAYS = ['fa fa-lightbulb-o', 'fa fa-bullhorn', 'icon-rocket', 'fa fa-connectdevelop', 'fa fa-skyatlas', 'icon-envelope',
        'icon-line icon-bar-chart', 'icon-handbag', 'icon-social-youtube', 'icon-diamond', 'icon-user', 'icon-settings', 'icon-star', 'icon-wrench', 'icon-earphones-alt',
        'icon-bubbles'];

    /**
     * Expand Row States
     */
    const ROW_NONE = -1;
    const ROW_EXPANDED = 0;
    const ROW_COLLAPSED = 1;

    /**
     * Alignment
     */
    // Horizontal Alignment
    const ALIGN_RIGHT = 'right';
    const ALIGN_CENTER = 'center';
    const ALIGN_LEFT = 'left';
    // Vertical Alignment
    const ALIGN_TOP = 'top';
    const ALIGN_MIDDLE = 'middle';
    const ALIGN_BOTTOM = 'bottom';
    // CSS for preventing cell wrapping
    const NOWRAP = 'kv-nowrap';

    // form inputs
    const INPUT_HIDDEN = 'hiddenInput';
    const INPUT_TEXT = 'textInput';
    const INPUT_TEXTAREA = 'textarea';
    const INPUT_PASSWORD = 'passwordInput';
    const INPUT_DROPDOWN_LIST = 'dropdownList';
    const INPUT_LIST_BOX = 'listBox';
    const INPUT_CHECKBOX = 'checkbox';
    const INPUT_RADIO = 'radio';
    const INPUT_CHECKBOX_LIST = 'checkboxList';
    const INPUT_RADIO_LIST = 'radioList';
    const INPUT_MULTISELECT = 'multiselect';
    const INPUT_STATIC = 'staticInput';
    const INPUT_FILE = 'fileInput';
    const INPUT_HTML5 = 'input';
    const INPUT_WIDGET = 'widget';
    const INPUT_RAW = 'raw'; // any free text or html markup
    const INPUT_HTML = 'html';
    const INPUT_NUMERIC = 'numeric';
    const INPUT_READONLY = 'readonly';
    const INPUT_INLINE = 'inline';

    const
        COLUMN_VIEW = 'kartik\grid\DataColumn',
        COLUMN_EDIT = 'kartik\grid\EditableColumn',
        EDITOR_BOOLEAN = '\kartik\checkbox\CheckboxX',
        EDITOR_BOOLEAN_SETTINGS = "'pluginOptions' => ['theme' => 'krajee-flatblue', 'size'=>'md', 'threeState'=>false]",

        EDITOR_DATE = '\kartik\widgets\DatePicker',
        EDITOR_DATE_SETTINGS = "'pluginOptions' => ['format' => FHtml::config(FHtml::SETTINGS_DATE_FORMAT, 'dd M yyyy'), 'class' => 'form-control', 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true ]",

        EDITOR_DATETIME = '\kartik\widgets\DateTimePicker',
        EDITOR_DATETIME_SETTINGS = "'convertFormat' => true, 'pluginOptions' => ['format' => FHtml::config(FHtml::SETTINGS_TIME_FORMAT, 'dd M yyyy hh:ii'), 'autoclose' => true, 'todayHighlight' => true, 'todayBtn' => true, 'daysOfWeekDisabled' => FHtml::config(FHtml::SETTINGS_DAYS_OF_WEEK_DISABLED, '0,6'), 'hoursDisabled' => FHtml::config(FHtml::SETTINGS_HOURS_DISABLED, '0,1,2,3,4,5,6,7,8,19,20,21,22')]",

        EDITOR_TIME = '\kartik\widgets\TimePicker',
        EDITOR_TIME_SETTINGS = " 'pluginOptions'=> ['showMeridian'=>false, 'showSeconds' => false, 'minuteStep' => 15]",

        EDITOR_TEXT = 'FCKEditor',
        EDITOR_TEXT_SETTINGS = "'options' => ['rows' => 5, 'disabled' => false], 'preset' => 'normal'",

        EDITOR_SELECT = '\kartik\widgets\Select2',
        EDITOR_SELECT_SETTINGS = "'pluginOptions' => ['allowClear' => true, 'tags' => true]",

        EDITOR_RATE = '\kartik\widgets\StarRating',
        EDITOR_RATE_SETTINGS = "'pluginOptions' => [ 'stars' => 5, 'min' => 0, 'max' => 5, 'step' => 1, 'showClear' => true, 'showCaption' => true, 'defaultCaption' => '{rating}', 'starCaptions' => [0 => '', 1 => 'Poor', 2 => 'OK', 3 => 'Good', 4 => 'Super', 5 => 'Extreme']]",

//        EDITOR_NUMERIC = '\kartik\widgets\TouchSpin',
//        EDITOR_NUMERIC_SETTINGS = "'pluginOptions' => [ 'initval' => 1, 'min' => 0, 'max' => 10000000000000000, 'step' => 1, 'decimals' => 0,  'verticalbuttons' => true, 'verticalupclass' => 'glyphicon glyphicon-plus', 'verticaldownclass' => 'glyphicon glyphicon-minus','prefix' => '', 'postfix' => '']",
//
//        EDITOR_CURRENCY = '\kartik\money\MaskMoney',
//        EDITOR_CURRENCY_SETTINGS = "'pluginOptions' => [ 'initval' => 1, 'min' => 0, 'max' => 999999999999999999, 'thousands' => ',',  'decimal' => '.', 'precision' => 2, 'allowZero' => true, 'allowNegative' => false, 'suffix' => '', 'prefix' => '', 'affixesStay' => false,]",

        EDITOR_NUMERIC = '\yii\widgets\MaskedInput',
        EDITOR_NUMERIC_SETTINGS = "'clientOptions' => ['alias' =>  'numeric', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]",

        EDITOR_SWITCH = 'kartik\switchinput\SwitchInput',
        EDITOR_SWITCH_SETTINGS = "",

        EDITOR_HTML = 'common\widgets\FCKEditor',
        EDITOR_HTML_SETTINGS = "",

        EDITOR_CURRENCY = '\yii\widgets\MaskedInput',
        EDITOR_CURRENCY_SETTINGS = "'clientOptions' => ['alias' =>  'decimal', 'groupSeparator' => ',', 'autoGroup' => true, 'removeMaskOnSubmit' => true]",

        EDITOR_COLOR = '\kartik\widgets\ColorInput',
        EDITOR_COLOR_SETTINGS = "",

        EDITOR_MASK = '\yii\widgets\MaskedInput',
        EDITOR_MASK_SETTINGS = "",

        EDITOR_SLIDE = 'kartik\slider\Slider',
        EDITOR_SLIDE_SETTINGS = "'sliderColor'=>Slider::TYPE_GREY, 'handleColor'=>Slider::TYPE_DANGER, 'pluginOptions'=>['min'=>0,'max'=>100,'step'=>1]",
        EDITOR_SLIDE_RANGE_SETTINGS = "'sliderColor'=>Slider::TYPE_GREY, 'handleColor'=>Slider::TYPE_DANGER, 'pluginOptions'=>['min'=>0,'max'=>100,'step'=>1,'range'=>true]",

        EDITOR_FILE = '\common\widgets\FFileInput',
        EDITOR_FILE_SETTINGS = "'pluginOptions' => ['browseLabel' => '', 'removeLabel' => '', 'previewFileType' => 'any', 'uploadUrl' => Url::to([FHtml::config('UPLOAD_FOLDER', '/site/file-upload')])]",

        EDITOR_RANGE = '\kartik\widgets\RangeInput',
        EDITOR_RANGE_SETTINGS = "'pluginOptions' => ['placeholder' => 'Rate (0 - 10)...', 'html5Options' => ['min' => 0, 'max' => 100], 'addon' => ['append' => ['content' => 'star']]]";

    const
        SUBMIT_TYPE = 'submit';
    const
        BUTTON_CREATE = 'create',
        BUTTON_UPDATE = 'update',
        BUTTON_DELETE = 'delete',
        BUTTON_PROCESS = 'processing',
        BUTTON_PENDING = 'pending',
        BUTTON_RESET = 'reset',
        BUTTON_SEARCH = 'search',
        BUTTON_EDIT = 'update',
        BUTTON_CANCEL = 'cancel',
        BUTTON_ADD = 'add',
        BUTTON_REMOVE = 'remove',
        BUTTON_SELECT = 'select',
        BUTTON_MOVE = 'move',
        BUTTON_RELOAD = 'reload',
        BUTTON_OK = 'ok',
        BUTTON_COPY = 'copy',
        BUTTON_ACCEPT = 'accept',
        BUTTON_REJECT = 'reject',
        BUTTON_APPROVED = 'approve',
        BUTTON_BACK = 'back',
        BUTTON_READ = 'read',
        BUTTON_UNREAD = 'unread',
        BUTTON_CONFIRM = 'confirm',
        BUTTON_COMPLETE = 'complete',
        BUTTON_REVERT = 'revert',
        BUTTON_SEND = 'send';


    const
        SHOW_LINK = 'link',
        SHOW_DATE = 'date',
        SHOW_LABEL = 'label',
        SHOW_BOOLEAN = 'boolean',
        SHOW_USER = 'user',
        SHOW_ROLE = 'role',
        SHOW_TIME = 'time',
        SHOW_TIMESTAMP = 'timestamp',
        SHOW_DATETIME = 'datetime',
        SHOW_PARENT = 'parentid',
        SHOW_NUMBER = 'number',
        SHOW_CURRENCY = 'currency',
        SHOW_DECIMAL = 'decimal',
        SHOW_LOOKUP = 'lookup',
        SHOW_RATE = 'rate',
        SHOW_EMAIL = 'email',
        SHOW_RANGE = 'range',
        SHOW_FILE = 'file',
        SHOW_TEXT = 'text',
        SHOW_MASK = 'mask',
        SHOW_DATE_FRIENDLY = 'date_friendly',
        SHOW_ACTIVE = 'active',
        SHOW_COLOR = 'color',
        SHOW_IMAGE = 'image',
        SHOW_HIDDEN = 'hidden',
        SHOW_HTML = 'html',
        SHOW_IFRAME = 'iframe',
        SHOW_VIDEO = 'video',
        SHOW_STYLESHEET = 'style',
        SHOW_SCRIPT = 'script',
        SHOW_WIDGET = 'widget',
        SHOW_VIEW = 'view',
        SHOW_GOOGLE_MAP = 'google_map';

    const COLORS = [0 => 'default', 1 => 'primary', 2 => 'success', 3 => 'warning', 4 => 'danger'];
    const COLORS_BACKGROUND_ARRAYS = ['u', 'red', 'blue', 'sea', 'yellow', 'dark', 'grey'];

    const
        LAYOUT_ONELINE = 'one_line',
        LAYOUT_NEWLINE = 'new_line',
        LAYOUT_ONELINE_RIGHT = 'one_line_right',
        LAYOUT_TEXT = 'text',
        LAYOUT_NO_LABEL = 'nolabel',
        LAYOUT_TABLE = 'table';

    const
        CSS_IMAGE_DEFAULT = 'img-responsive',
        CSS_IMAGE_ROUND = 'img-responsive rounded-x',
        CSS_IMAGE_ANIMATED_SWING = 'wow swing img-responsive',
        CSS_IMAGE_ANIMATED_FADE = 'wow fadeIn img-responsive',
        CSS_IMAGE_ANIMATED_BOUNCE = 'wow bounceIn img-responsive',
        CSS_IMAGE_ANIMATED_PULSE = 'wow pulse img-responsive',
        CSS_IMAGE_ANIMATED_ROLL = 'wow rollIn img-responsive',
        CSS_IMAGE_ANIMATED_ZOOM = 'wow zoomIn img-responsive',
        CSS_IMAGE_ROUND_BORDER = 'img-responsive img-bordered rounded-x',
        CSS_IMAGE_SQUARE_BORDER = 'img-responsive img-bordered rounded-2x',
        CSS_IMAGE_SQUARE = 'img-responsive rounded-2x',
        CSS_DIV_SHADOW_BORDER = 'box-shadow shadow-effect-1 rounded-2x',
        CSS_DIV_SHADOW = 'box-shadow shadow-effect-1';

    const CSS_ANIMATED_ARRAYS = ['swing', 'fadeIn', 'bounceIn', 'pulse', 'rollIn', 'zoomIn'];

    const WIDGET_WIDTH_FULL = 'full',
        WIDGET_WIDTH_CONTAINER = 'container',
        WIDGET_BACKGROUND_PARRALAX = 'parallax-team parallaxBg',
        WIDGET_BACKGROUND_PARRALAX1 = 'parallax-counter-v1 parallaxBg',
        WIDGET_BACKGROUND_PARRALAX2 = 'parallax-counter-v2 parallaxBg',
        WIDGET_BACKGROUND_PARRALAX3 = 'parallax-counter-v3 parallaxBg',
        WIDGET_BACKGROUND_PARRALAX4 = 'parallax-counter-v4 parallaxBg',
        WIDGET_BACKGROUND_DARK = 'twitter-block parallaxBg';

    const WIDGET_BACKGROUND_ARRAYS = [
        '',
        'parallax-team parallaxBg', 'parallax-counter-v1 parallaxBg', 'parallax-counter-v2 parallaxBg',
        'parallax-counter-v3 parallaxBg', 'parallax-counter-v4 parallaxBg', 'twitter-block parallaxBg'
    ];

    const
        HEADLINE_TYPE_LEFT = 'headline',
        HEADLINE_TYPE_CENTER = 'heading',
        HEADLINE_TYPE_CENTER_V1 = 'heading heading-v1',
        HEADLINE_TYPE_CENTER_V2 = 'heading heading-v2',
        HEADLINE_TYPE_TITLE2 = 'title-box-v2',
        HEADLINE_TYPE_TITLE1 = 'title-box-v1';

    const
        SETTINGS_EXCLUDED = ['ADMIN_INLINE_EDIT', 'View', 'Form', 'Index', 'Index Print', FHtml::SETTINGS_ADMIN_INLINE_EDIT, 'ADMIN_THUMBNAIL_WIDTH', FHtml::SETTINGS_THUMBNAIL_SIZE, 'UPLOAD_FOLDER', FHtml::SETTINGS_FORM_LAYOUT, FHtml::SETTINGS_ADMIN_MENU_OPEN],
        SETTINGS_FORM_VIEW_POPUP = 'FORM_VIEW_POPUP',
        SETTINGS_FORM_EDIT_POPUP = 'FORM_EDIT_POPUP',
        SETTINGS_GRID_BUTTONS_TYPE = 'Buttons Style',
        SETTINGS_FORM_TYPE = 'Form Style',
        SETTINGS_ADMIN_INLINE_EDIT = 'Allow Inline Edit',
        SETTINGS_ADMIN_MODULES = 'Admin Menu Modules',
        SETTINGS_FIELD_LAYOUT = 'Field Layout',
        SETTINGS_FORM_LAYOUT = 'Form Layout',
        SETTINGS_AJAX_ENABLED = 'AJAX_ENABLED',
        SETTINGS_MATERIAL_DESIGN = 'Theme Style',
        SETTINGS_CONTROLS_HAS_ROUND_BORDER = 'CONTROLS_HAS_ROUND_BORDER',
        SETTINGS_PORTLET_STYLE = 'Portlet - Style',
        SETTINGS_DISPLAY_PORTLET = 'SETTINGS_DISPLAY_PORTLET',
        SETTINGS_MAIN_COLOR = 'main_color',
        SETTINGS_LANG = 'lang',
        SETTINGS_ADMIN_MAIN_COLOR = 'Theme Color',
        SETTINGS_FORM_CONTROLS_ALIGNMENT = 'Controls Alignment',
        SETTINGS_DISPLAY_PAGECONTENT_HEADER = 'DISPLAY_PAGECONTENT_HEADER',
        SETTINGS_PAGE_SIZE = 'Page Size',
        SETTINGS_CURRENCY = 'Format Currency',
        SETTINGS_DATE_FORMAT = 'Format Date',
        SETTINGS_THUMBNAIL_SIZE = 'thumbnail_size',
        SETTINGS_DAYS_OF_WEEK_DISABLED = 'DAYS_OF_WEEK_DISABLED',
        SETTINGS_HOURS_DISABLED = 'HOURS_DISABLED',
        SETTINGS_DATETIME_FORMAT = 'Format Datetime',
        SETTINGS_COMPANY_NAME = 'name',
        SETTINGS_COMPANY_COPYRIGHT = 'copyright',
        SETTINGS_COMPANY_PRIVACY = 'privacy',
        SETTINGS_COMPANY_TERMS_OF_SERVICE = 'terms_of_service',
        SETTINGS_COMPANY_PROFILE = 'profile',
        SETTINGS_COMPANY_DOMAIN = 'website',
        SETTINGS_COMPANY_LOGO = 'logo',
        SETTINGS_COMPANY_FAVICON = 'favicon',
        SETTINGS_COMPANY_FAX = 'fax',
        SETTINGS_COMPANY_SLOGAN = 'slogan',
        SETTINGS_COMPANY_DESCRIPTION = 'description',
        SETTINGS_COMPANY_KEYWORD = 'keyword',
        SETTINGS_COMPANY_EMAIL = 'email',
        SETTINGS_COMPANY_WEBSITE = 'website',
        SETTINGS_COMPANY_ADDRESS = 'address',
        SETTINGS_COMPANY_MAP = 'map',
        SETTINGS_COMPANY_PHONE = 'phone',
        SETTINGS_COMPANY_CHAT = 'chat',
        SETTINGS_COMPANY_FACEBOOK = 'facebook',
        SETTINGS_COMPANY_TWITTER = 'twitter',
        SETTINGS_COMPANY_GOOGLE = 'google',
        SETTINGS_COMPANY_YOUTUBE = 'youtube',
        SETTINGS_PAGE_TITLE = 'page_title',
        SETTINGS_PAGE_DESCRIPTION = 'Page Description',
        SETTINGS_PAGE_IMAGE = 'Page Image',
        SETTINGS_SITE_ANALYTICS = 'Google Analytics',
        SETTINGS_TIME_FORMAT = 'time_format',
        SETTINGS_WEB_THEME = 'web_theme',
        SETTINGS_BODY_CSS = 'body_css',
        SETTINGS_BODY_STYLE = 'body_style',
        SETTINGS_FRONTEND_INDEX_FILE = 'Frontend Index File',
        SETTINGS_PAGE_CSS = 'page_css',
        SETTINGS_PAGE_STYLE = 'page_style',
        SETTINGS_GOOGLE_API_KEY = 'Google API Key',
        SETTINGS_ADMIN_MENU_OPEN = 'ADMIN_MENU_OPEN',
        SETTINGS_CACHE_ENABLED = 'Cache Enabled',
        SETTINGS_DEFAULT_FRONTEND_MODULE = 'Default Frontend Module',
        SETTINGS_IS_CART_ENABLED = 'Shopping Cart Enabled',
        SETTINGS_PAYPAL_API_USERNAME = 'Paypal API Username',
        SETTINGS_PAYPAL_API_EMAIL = 'Paypal API Email',
        SETTINGS_PAYPAL_API_PASSWORD = 'Paypal API Password',
        SETTINGS_PAYPAL_API_SIGNATURE = 'Paypal API Signature',
        SETTINGS_PAYPAL_API_LIVE = 'Paypal API LIVE MODE',

    SETTINGS_PEM_FILE = 'PEM_FILE';

    const
        CONFIG_DB = 'db';
    const COLORS_PALETTE_SIMPLE_OPTIONS = [
        'showPalette' => true,
        'showPaletteOnly' => true,
        'showSelectionPalette' => true,
        'showAlpha' => false,
        'allowEmpty' => true,
        'preferredFormat' => 'name',
        'palette' => [
            [
                "white", "black", "grey", "silver", "gold", "brown",
            ],
            [
                "red", "orange", "yellow", "indigo", "maroon", "pink"
            ],
            [
                "blue", "green", "violet", "cyan", "magenta", "purple",
            ],
        ]
    ];

    protected static $buttonIcons = array(
        self::BUTTON_CREATE => 'fa fa-plus',
        self::BUTTON_SEARCH => 'fa fa-search',
        self::BUTTON_APPROVED => 'fa fa-check',
        self::BUTTON_UPDATE => 'fa fa-save',
        self::BUTTON_DELETE => 'fa fa-trash',
        self::BUTTON_RESET => 'fa fa-refresh',
        self::BUTTON_EDIT => 'fa fa-pencil',
        self::BUTTON_CANCEL => 'fa fa-cancel',
        self::BUTTON_COPY => 'fa fa-copy',
        self::BUTTON_ADD => 'fa fa-plus',
        self::BUTTON_REMOVE => 'fa fa-trash',
        self::BUTTON_SELECT => 'fa fa-share',
        self::BUTTON_MOVE => 'fa fa-move',
        self::BUTTON_OK => 'fa fa-ok',
        self::BUTTON_ACCEPT => 'fa fa-plus',
        self::BUTTON_REJECT => 'fa fa-lock',
        self::BUTTON_APPROVED => 'fa fa-ok-sign',
        self::BUTTON_BACK => 'fa fa-arrow-left',
        self::BUTTON_READ => 'fa fa-bookmark',
        self::BUTTON_UNREAD => 'fa fa-bookmark',
        self::BUTTON_CONFIRM => 'fa fa-signin',
        self::BUTTON_COMPLETE => 'fa fa-remove',
        self::BUTTON_REVERT => 'fa fa-share',
        self::BUTTON_SEND => 'm-fa fa-swapright',
        self::BUTTON_PROCESS => 'fa fa-play',
        self::BUTTON_PENDING => 'fa fa-pause',
    );

    public static $imageExtension = array('jpg', 'png', 'gif');


}