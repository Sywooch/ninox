<?php
return [
    'language'  =>  'ru_RU',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
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
	    'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
