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
    'printer'       =>  'backend\modules\printer\Module',
    'cashboxes'     =>  'backend\modules\cashboxes\Module',
    'pricelists' => [
        'class' => 'backend\modules\pricelists\Module',
    ],
    'test' => [
        'class' => 'backend\modules\test\Module',
    ],
    'promocodes' => [
        'class' => 'backend\modules\promocodes\Module',
    ],
    'gridview' =>  [
        'class' => '\kartik\grid\Module'
    ],
    'treemanager' =>  [
        'class' => '\kartik\tree\Module',
        // other module settings, refer detailed documentation
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