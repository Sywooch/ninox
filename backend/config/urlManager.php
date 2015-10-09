<?php
return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules' =>  [
        '<action:(login|logout)>'   =>  'site/<action>',
        '<module>'                  =>  '<module>/default/index',
        '<module>/<action>'         =>  '<module>/default/<action>',
        '<module>/<action>/<param>' =>  '<module>/default/<action>',
    ]
];