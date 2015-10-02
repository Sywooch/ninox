<?php
return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules' =>  [
        '<action:(login|logout)>'   =>  'site/<action>',
        '<module>'                  =>  '<module>/index',
        '<module>/<action>'         =>  '<module>/<action>'
    ]
];