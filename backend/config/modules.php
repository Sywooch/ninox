<?php
return [
    'goods'         =>  'backend\modules\goods\Module',
    'banners'       =>  'backend\modules\banners\Module',
    'blog'          =>  'backend\modules\blog\Module',
    'charts'        =>  'backend\modules\charts\Module',
    'customers'     =>  'backend\modules\customers\Module',
    'feedback'      =>  'backend\modules\feedback\Module',
    'lang'          =>  'backend\modules\lang\Module',
    'login'         =>  'backend\modules\login\Module',
    'orders'        =>  'backend\modules\orders\Module',
    'pricerules'    =>  'backend\modules\pricerules\Module',
    'tasks'         =>  'backend\modules\tasks\Module',
    'users'         =>  'backend\modules\users\Module',
    'store'         =>  'backend\modules\store\Module',
    'test' => [
        'class' => 'backend\modules\test\Module',
    ],
    'promocodes' => [
        'class' => 'backend\modules\promocodes\Module',
    ],
    'cashbox' => [
        'class' => 'backend\modules\cashbox\Module',
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