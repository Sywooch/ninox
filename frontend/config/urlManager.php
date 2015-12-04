<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        '<action:(addtocart|removefromcart|order|getcart)>' =>  'site/<action>',
        '<action:(log(in|out))>'   =>  'site/<action>',
        '<url:(.*)>'        =>  'site/renderpage'
    ]
];