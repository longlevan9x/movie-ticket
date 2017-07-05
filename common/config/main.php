<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ],
        //Config I18N for label  can be moved to common/config/main.php to use for both backend and frontend
        'i18n' => [
            'translations' => [
                'auth*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'auth' => 'auth.php',
                    ],
                ],
                'common*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'common' => 'common.php',
                    ],
                ],
            ],
        ],
        //'i18n message database
//        'i18n' => [
//            'translations' => [
//                '*' => [
//                    'class' => 'yii\i18n\DbMessageSource',
//                    'db' => 'db',
//                    'sourceLanguage' => 'xx-XX', // Developer language
//                    'sourceMessageTable' => '{{%language_source}}',
//                    'messageTable' => '{{%language_translate}}',
//                    'cachingDuration' => 86400,
//                    'enableCaching' => true,
//                ],
//            ],
//        ],
    ],
//    'language' => 'en',
];
