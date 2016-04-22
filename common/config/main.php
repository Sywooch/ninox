<?php
return [
    'language'  =>  'ru_RU',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'timeZone'	=>	'UTC',
    'components' => [
		'formatter' =>  [
			'class' =>  'common\components\Formatter'
		],
		'request'	=>	[
			'class'	=>	'common\components\Request'
		],
		'db'	=>	[
			'enableSchemaCache'	=>	true,
			'schemaCacheDuration' => 3600,
			'schemaCache' => 'cache',
		],
		'i18n' => [
			'translations' => [
				'shop-info-*' => [
					'class' => 'yii\i18n\DbMessageSource',
					//'basePath' => '@common/messages', // if advanced application, set @frontend/messages
					'sourceLanguage' => 'code',
					//'fileMap' => [
						//'main' => 'main.php',
					//],
				],
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
		'log'	=>	[
			'targets'	=>	[
				[
					'class'		=>	'yii\log\EmailTarget',
					'levels'	=>	['error'],
					'mailer'	=>	'mailer',
					'message'	=>	[
						'from'		=>	['krasotastyleyii@gmail.com', 'Krasota-Style'],
						'to'		=>	['krasotastyleyii@gmail.com', 'n.gilko@gmail.com'],
						'subject'	=>	"Ошибки на сайте ".\Yii::$app->name." ".date('d.m.Y H:i:s')
					]
				]
			],
			'flushInterval'	=>	'1000',
		],
	    'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
