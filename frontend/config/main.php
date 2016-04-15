<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'assetManager'  =>  [
            'linkAssets'    =>  true,
            'bundles' => [
                'yii2mod\slider\IonSliderAsset' => [
                    'css' => [
                        'css/ion.rangeSlider.css',
                        'css/ion.rangeSlider.skinModern.css'
                    ]
                ],
            ],
        ],
        'cache' =>  [
            'class' =>  'yii\caching\FileCache'
        ],
        'cart'  =>  [
            'class' =>  'frontend\components\Cart'
        ],
        'email'  =>  [
            'class' =>  'frontend\components\Email'
        ],
        'sms'  =>  [
            'class' =>  'frontend\components\Sms'
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'ru_RU',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
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
        'account' => [
            'class' => 'frontend\modules\account\Module',
        ],
        'autopricelist' => [
            'class' => 'frontend\modules\autopricelist\Module',
        ],
	    'gridview' =>  [
		    'class' => '\kartik\grid\Module'
	    ],
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['80.78.45.114', '127.0.0.1', '::1'],
            'panels' => [
                'views' => ['class' => 'common\panels\ViewsPanel'],
                'version' => ['class' => 'common\panels\VersionPanel'],
            ],
        ],
    ],
    'params' => $params,
    'on beforeRequest' => function () {
        $pathInfo = Yii::$app->request->pathInfo;
        $query = Yii::$app->request->queryString;
        if (!empty($pathInfo) && substr($pathInfo, -1) === '/') {
            $url = '/' . substr($pathInfo, 0, -1);
            if ($query) {
                $url .= '?' . $query;
            }
            Yii::$app->response->redirect($url, 301);
            Yii::$app->end();
        }
    },
];
