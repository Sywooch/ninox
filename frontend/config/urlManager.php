<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        'order'             =>  'site/order',
        '<url:(.*)>'        =>  'site/renderpage'
    ]
];