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
		'dbBlog'	=>	[
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
					'class' => 'yii\log\FileTarget',
					'exportInterval' => 1,
				],
				[
					'class'		=>	'yii\log\EmailTarget',
					'levels'	=>	['error'],
					'mailer'	=>	'mailer',
					'exportInterval'	=>	10,
					'message'	=>	[
						'from'		=>	['krasotastyleyii@gmail.com'],
						'to'		=>	['krasotastyleyii@gmail.com'],
						'subject'	=>	"Ошибки на сайте dev.k-s.com.ua ".date('d.m.Y H:i:s')
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
