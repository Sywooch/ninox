<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        '<module(autopricelist)>/<id>'   =>  '<module>/default/index',
        '<action:((modify|get)cart|order|search|setitemrate|addtowishlist)>' => 'site/<action>',
        '<action:(log(in|out)|register|request-password-reset|captcha(.*))>'   =>  'site/<action>',
        '<action:(reset-password)>/<token>'      =>   'site/<action>',
        '<module(account)>'          =>  '<module>/default/index',
        '<module(account)>/<action>'          =>  '<module>/default/<action>',
        '<url:(.*)>/order-<order:(\w+)>/page-<page:(\d+)>'    =>  'site/renderpage',
        '<url:(.*)>/page-<page:(\d+)>'    =>  'site/renderpage',
        '<url:(.*)>/order-<order:(\w+)>'    =>  'site/renderpage',
        '<url:(.*)>'        =>  'site/renderpage',
    ]
];