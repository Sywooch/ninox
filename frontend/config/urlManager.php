<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        '<action:(addtocart|removefromcart|order|getcart)>' =>  'site/<action>',
        '<module>'          =>  '<module>/default/index',
        '<module>/<actoin>'          =>  '<module>/default/<action>',
        '<url:(.*)>'        =>  'site/renderpage'
    ]
];