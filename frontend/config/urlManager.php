<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        '<action:(addtocart|changeitemcount|order)>' =>  'site/<action>',
        '<url:(.*)>'        =>  'site/renderpage'
    ]
];