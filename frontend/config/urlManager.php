<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        '<module(autopricelist)>/<id>'   =>  '<module>/default/index',
        '<action:((modify|get)cart|order|search)>' =>  'site/<action>',
        '<action:(log(in|out)|register|request-password-reset|captcha(.*))>'   =>  'site/<action>',
        '<action:(reset-password)>/<token>'      =>   'site/<action>',
        '<module(account)>'          =>  '<module>/default/index',
        '<module(account)>/<action>'          =>  '<module>/default/<action>',
        '<url:(.*)>'        =>  'site/renderpage'
    ]
];