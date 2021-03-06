<?php
return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules' =>  [
        '<action:(log(in|out)|updatecurrency)>'    =>  'site/<action>',
        '<action:(add(controller|action))>'   =>  'site/<action>',
        '<action:(loadchat)>'           =>  'site/<action>',
        '<module>'                  =>  '<module>/default/index',
        '<module>/<action>'         =>  '<module>/default/<action>',
        '<module>/<action>/<param>' =>  '<module>/default/<action>',
    ]
];