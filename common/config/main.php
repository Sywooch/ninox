<?php
return [
    'language'  =>  'ru',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
		'request'	=>	[
			'class'	=>	'common\components\Request'
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
					'sourceLanguage' => 'ru',
					'fileMap' => [
						//'main' => 'main.php',
					],
				],
			],
		],
	    'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
