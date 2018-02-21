<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'app-console',
    'name'=>'Easy Forms',
    'version' => '1.6.3',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii', 'app\components\Bootstrap'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'settings' => [
            'class' => 'app\components\Settings'
        ],
    ],
    'params' => $params,
];
