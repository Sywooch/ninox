<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-cashbox',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'cashbox\controllers',
    'bootstrap' => ['log'],
    'modules' => require(__DIR__.'/modules.php'),
    'components' => [
        'cashbox'   =>  [
            'class' =>  'cashbox\components\CashboxNoCache'
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'ru-RU',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'user' => [
            'identityClass'     => 'cashbox\models\User',
            'enableAutoLogin'   => true,
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => require(__DIR__.'/urlManager.php'),
    ],
    'modules'   =>  [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['80.78.45.114', '127.0.0.1', '::1'],
            'panels' => [
                'views' => ['class' => 'common\panels\ViewsPanel'],
                'version' => ['class' => 'common\panels\VersionPanel'],
                'userIP' => ['class' => 'common\panels\UserIPPanel'],
            ],
        ],
    ],
    'params' => $params,
];
