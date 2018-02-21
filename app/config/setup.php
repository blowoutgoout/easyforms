<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'app',
    'name'=>'Easy Forms',
    'version' => '1.6.3',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'setup/step/1',
    'timezone' => 'UTC',
    'sourceLanguage' => 'en-US',
    'bootstrap' => ['setup'],
    'components' => [
        'request' => [
            // Change this secret key - this is required for cookie validation
            'cookieValidationKey' => 'PEi6ICsok3vWiJSJJtQV2JZ6D-jk5gkh',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => false,
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
        'setup' => [
            'class' => 'app\modules\setup\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
