<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        '<action:(addtocart|removefromcart|order|getcart)>' =>  'site/<action>',
        '<url:(.*)>'        =>  'site/renderpage'
    ]
];