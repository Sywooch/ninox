<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        'order'             =>  'site/order',
        '<action:(addtocart)>' =>  'site/<action>',
        '<action:(changeitemcount)>' =>  'site/<action>',
        '<url:(.*)>'        =>  'site/renderpage'
    ]
];