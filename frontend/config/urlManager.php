<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        'order'             =>  'site/order',
        '<action:(addtocart|changeitemcount)>' =>  'site/<action>',
        '<url:(.*)>'        =>  'site/renderpage'
    ]
];