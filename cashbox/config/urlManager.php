<?php
return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules' =>  [
        ''  =>  'site/index',
        '<action>'         =>  'site/<action>',
        '<action>/<param>' =>  'site/<action>',
    ]
];