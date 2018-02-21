<?php

use kartik\datecontrol\Module as DateControlModule;

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'app',
    'name'=>'Easy Forms',
    'version' => '1.6.3',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'dashboard/index',
    'timeZone' => 'UTC',
    'language' => 'en-US',
    'sourceLanguage' => 'en-US',
    'bootstrap' => ['log', 'app\components\Bootstrap', 'addons'],
    'components' => [
        'request' => [
            // Change this secret key in the following - this is required for cookie validation
            'cookieValidationKey' => 'PEi6ICsok3vWiJSJJtQV2JZ6D-jk5gkh',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'settings' => [
            'class' => 'app\components\Settings'
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\User',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/modules/user/views' => '@app/views/user/views',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'UTC',
            'dateFormat' => 'php:d-M-Y',
            'datetimeFormat' => 'php:d-M-Y H:i:s',
            'timeFormat' => 'php:H:i:s',
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'forms/<slug>/<b:[0-1]>/<t:[0-1]>/<js:[0-1]>/<rec:[0-1]>' => 'app/forms',
                'forms/<slug>/<b:[0-1]>/<t:[0-1]>/<js:[0-1]>' => 'app/forms',
                'forms/<slug>/<b:[0-1]>/<t:[0-1]>' => 'app/forms',
                'forms/<slug>/<b:[0-1]>' => 'app/forms',
                'forms/<slug>' => 'app/forms',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'submissions'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'rules'],

            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web/static_files/',
                    'js' => [
                        'js/libs/jquery.js', // v1.11.2
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web/static_files/',
                    'css' => [
                        'css/fonts.min.css',
                        'css/bootstrap.min.css', // v3.3.5
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web/static_files/',
                    'js' => [
                        'js/libs/bootstrap.min.js', // v3.3.5
                    ],
                ],
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'app\modules\user\Module',
            'controllerMap' => [
                'default' => 'app\controllers\UserController',
                'admin' => 'app\controllers\user\AdminController',
            ],
            'modelClasses'  => [
                'User' => 'app\models\User',
                'Role' => 'app\models\Role',
                'UserSearch' => 'app\models\search\UserSearch',
                'Profile' => 'app\models\Profile',
                'ForgotForm' => 'app\models\forms\ForgotForm',
                'LoginEmailForm' => 'app\models\forms\LoginEmailForm',
            ],
        ],
        // Only Uncomment the following line for update process
        // 'update' => ['class' => 'app\modules\update\Module'],
        'addons' => [
            'class' => 'app\modules\addons\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',
            'displaySettings' => [
                DateControlModule::FORMAT_DATE => 'dd-MM-yyyy',
                DateControlModule::FORMAT_TIME => 'HH:mm:ss a',
                DateControlModule::FORMAT_DATETIME => 'dd-MM-yyyy HH:mm:ss',
            ],
            'saveSettings' => [
                DateControlModule::FORMAT_DATE => 'php:U', // saves as unix timestamp
                DateControlModule::FORMAT_TIME => 'php:H:i:s',
                DateControlModule::FORMAT_DATETIME => 'php:U',
            ],
            'autoWidget' => true,
            'saveTimezone' => 'UTC',
            'ajaxConversion' => true,
            'autoWidgetSettings' => [
                DateControlModule::FORMAT_DATE => [
                    'type' => 2,
                    'buttonOptions' => ['class'=>'btn btn-primary'],
                    'pluginOptions' => ['autoclose'=>true]
                ],
                DateControlModule::FORMAT_DATETIME => [], // setup if needed
                DateControlModule::FORMAT_TIME => [], // setup if needed
            ],
            'widgetSettings' => [
                DateControlModule::FORMAT_DATE => [
                    'class' => 'yii\jui\DatePicker', // example
                    'options' => [
                        'dateFormat' => 'php:d-M-Y',
                        'options' => ['class'=>'form-control'],
                    ]
                ]
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV && YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
