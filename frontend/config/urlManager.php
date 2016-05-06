<?php

return [
    'enablePrettyUrl'   =>  true,
    'showScriptName'    =>  false,
    'rules'             =>  [
        ''                  =>  'site/index',
        'tovar/<link>'      =>  'site/showtovar',
        '<module(autopricelist)>/<id>'   =>  '<module>/default/index',
        '<action:(search)>/<string:(.*)>/page-<page:(\d+)>' => 'site/<action>',
        '<action:(search)>/<string:(.*)>' => 'site/<action>',
        '<action:((modify|get)cart|order|setitemrate|addtowishlist)>' => 'site/<action>',
        '<action:(log(in|out)|register|subscribe|request-password-reset|captcha(.*))>'   =>  'site/<action>',
        '<action:(reset-password)>/<token>'      =>   'site/<action>',
        '<module(account|blog)>'          =>  '<module>/default/index',
        '<module(account)>/<action>'          =>  '<module>/default/<action>',
        '<module(blog)>/<action:(lastnews)>'          =>  '<module>/default/<action>',
        '<module(blog)>/<url:(.*)>'          =>  '<module>/default/route',
        '<url:(.*)>/order-<order:(\w+)>/page-<page:(\d+)>'    =>  'site/renderpage',
        '<url:(.*)>/page-<page:(\d+)>'    =>  'site/renderpage',
        '<url:(.*)>/order-<order:(\w+)>'    =>  'site/renderpage',
        '<url:(.*)>'        =>  'site/renderpage',
    ]
];