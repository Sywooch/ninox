<?php
return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules' =>  [
        '<action:(login|logout)>'   =>  'site/<action>',
        ''                          =>  'orders/default/index',
        '<module>'                  =>  '<module>/index',
        '<module>/<action>'         =>  '<module>/<action>',
        '<module>/<action>/<param>' =>  '<module>/<action>',
    ]
];