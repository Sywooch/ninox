<?php
return [
    'cashbox' => [
        'class' => 'cashbox\modules\cashbox\Module',
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
];