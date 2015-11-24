<?php
return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules' =>  [
        '<action:(log(in|out))>'   =>  'site/<action>',
        '<action:(add(controller|action))>'   =>  'site/<action>',
        '<module>'                  =>  '<module>/default/index',
        '<module>/<action>'         =>  '<module>/default/<action>',
        '<module>/<action>/<param>' =>  '<module>/default/<action>',
    ]
];