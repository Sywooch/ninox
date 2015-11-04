<?php
use backend\widgets\ServiceMenuWidget;
use bobroid\yamm\Yamm;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */


$ordersPage = $this->title == 'Заказы';

$js = <<<'SCRIPT'
$(function () {
    $("[data-toggle='tooltip']").tooltip();
});;
/* To initialize BS3 popovers set this below */
$(function () {
    $("[data-toggle='popover-x']").popover();
});

var a = document.querySelectorAll("#currencyModal i.change"),
    b = document.querySelectorAll("#currencyModal i.save"),
    c = document.querySelectorAll("#currencyModal i.cancel"),
    returnView = function(e){
        var n = e.currentTarget.parentNode.parentNode;
        n.parentNode.querySelector(".view").style.display = "block";
        n.style.display = "none";
    }, guestRefresh = function(){
        $.ajax({
            type: 'POST',
            url: '/login',
            success: function(data){
                if(data == 1){
                    location.reload();
                }
            }
        });
    };

setInterval(guestRefresh, 10000);

for(var i = 0; i < a.length; i++){
    a[i].addEventListener("click", function(e){
        var n = e.currentTarget.parentNode.parentNode;

        n.parentNode.querySelector(".edit").style.display = "block";

        n.style.display = "none";
    }, false);
}

for(i = 0; i < b.length; i++){
    b[i].addEventListener("click", function(e){
        returnView(e);
    }, false);
}

for(i = 0; i < c.length; i++){
    c[i].addEventListener("click", function(e){
        returnView(e);
    }, false);
}
SCRIPT;

$css = <<<'STYLE'
.top-nav li{
    font-size: 12px;
}

i.large{
    font-size: 30px;
    margin-top: -20px;
    top: 12px;
}

#currencyModal .edit{
    display: none;
}

span.twitter-typeahead div.tt-menu{
    margin-top: -1px;
    border-radius: 0px;
    border-left: 0px none;
    border-right: 0px none;
}

.tt-scrollable-menu .tt-menu{
    max-height: none;
    overflow-y: none;
}

.tt-menu .typeahead-list-item{
    color: rgba(0, 0, 0, 0.4);
    font-size: 11px;
}

.tt-menu .typeahead-list-item .name{
    font-size: 13px;
}

.tt-menu .typeahead-list-item .category{
    color: rgba(0, 0, 0, 0.4);
}

.tt-suggestions > *{
    border-bottom: 1px solid rgba(0, 0, 0, 0.4);
}

.tt-suggestions > *:last-child{
    border-bottom: 0px;
}
STYLE;

\Yii::$app->params['sideTabs'][] = [
    'position'  =>  'right',
    'label'     =>  'Нашли баг?',
    'options'   =>  [
        'class'     =>  'size-small',
        'onclick'   =>  "$('#bugReportWidget').modal('show')"
    ]
];

$this->registerCss($css);
$this->registerJs($js);

\backend\assets\AppAsset::register($this);

rmrevin\yii\fontawesome\AssetBundle::register($this);
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width; initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="https://<?=$_SERVER['SERVER_NAME']?>/favicon.ico">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    Yamm::begin([
        'typeaheadSearch'   =>  true,
        'typeaheadConfig'   =>  [
            'name' => 'country_1',
            'options' => ['placeholder' => 'Начните вводить текст для поиска...'],
            'scrollable' => true,
            'pluginOptions' => ['highlight'=>true],
            'dataset' => [
                [
                    'remote' => [
                        'url' => Url::to(['/goods/searchgoods']) . '?string=%QUERY',
                        'wildcard' => '%QUERY'
                    ],
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                    'display' => 'value',
                    'templates' => [
                        'notFound' => '<div class="text-danger" style="padding:0 8px">'.\Yii::t('admin', 'По вашему запросу ничего не найдено!').'</div>',
                        'suggestion' => new JsExpression('Handlebars.compile(\'<a class="typeahead-list-item" href="/goods/showgood/{{ID}}"><div class="row"><div class="col-xs-12 name">{{Name}}</div><div class="col-xs-12 category"><span class="pull-right ">{{categoryname}}</span></div><div class="col-xs-12 code">Код товара: {{Code}}</div></div></a>\')')
                    ]
                ]
            ]
        ],
        'options' => [
            'headerOptions'   =>  [
                'class'   =>  'gray'
            ]
        ],
        'theme' =>  'gray',
        'items' => [
            [
                'label'     => FA::icon('check-circle-o')->size(FA::SIZE_2X).'<span class="visible-lg-inline visible-xs-inline">&nbsp;Заказы</span>',
                'url'       => Url::home(),
                'counter'   =>  '25',
                'options'   =>  [
                    'class' =>  'bordered'
                ]
            ],
            [
                'label'     => FA::icon('list-alt')->size(FA::SIZE_2X).'<span class="visible-lg-inline visible-xs-inline">&nbsp;Товары</span>',
                'url'       => Url::toRoute('/goods/index'),
                'options'   =>  [
                    'class' =>  'bordered'
                ]
            ],
            [
                'label'     => FA::icon('arrow-circle-o-up')->size(FA::SIZE_2X).'<span class="visible-lg-inline visible-xs-inline">&nbsp;Отправка</span>',
                'url'       => Url::toRoute([
                    Url::home(),
                    'status'    =>  'delivery'
                ]),
                'counter'   =>  '0',
                'options'   =>  [
                    'class' =>  'bordered'
                ]
            ],
            [
                'label' => FA::icon('bars'),
                'url' => ['#'],
                'items' =>  [
                    [
                        'label' =>  'Магазин',
                        'items' =>  [
                            [
                                'label' =>  'Товары',
                                'items' =>  [
                                    [
                                        'label' =>  'Все товары',
                                        'url'   =>  Url::toRoute('/goods/index')
                                    ],
                                    [
                                        'label' =>  'Отключеные товары',
                                        'url'   =>  Url::toRoute([
                                            '/goods/index',
                                            'smartfilter'   =>  'disabled'
                                        ])
                                    ],
                                    [
                                        'label' =>  'Товары без цены',
                                        'url'   =>  Url::toRoute([
                                            '/goods/index',
                                            'smartfilter'   =>  'withoutprice'
                                        ])
                                    ],
                                    [
                                        'label' =>  'Товары без дополнительных фотографий',
                                        'url'   =>  Url::toRoute([
                                            '/goods/index',
                                            'smartfilter'   =>  'withoutalternatephotos'
                                        ])
                                    ],
                                    [
                                        'label' =>  'Дублирующиеся товары',
                                        'url'   =>  Url::toRoute([
                                            '/goods/index',
                                            'smartfilter'   =>  'duplicated'
                                        ])
                                    ],
                                    [
                                        'label' =>  'Рейтинг товаров',
                                        'url'   =>  Url::toRoute([
                                            '/goods/rating'
                                        ])
                                    ],
                                    [
                                        'label' =>  'Лог изменений товаров',
                                        'url'   =>  Url::toRoute(['/goods/log', 'act' => 'goodschanges'])
                                    ],
                                    [
                                        'label' =>  'Лог загрузок фото',
                                        'url'   =>  Url::toRoute(['/goods/log', 'act' => 'photosupload'])
                                    ],
                                    [
                                        'label' =>  'Приёмка товаров',
                                        'url'   =>  Url::toRoute('/goods/take')
                                    ],
                                    [
                                        'label' =>  'Опции товаров',
                                        'url'   =>  Url::toRoute('/goods/options')
                                    ],
                                    [
                                        'label' =>  'Отзывы на товары',
                                        'url'   =>  Url::toRoute('/goods/reviews')
                                    ],
                                    [
                                        'label' =>  'Запросы на отключение товаров',
                                        'items' =>  [
                                            [
                                                'label' =>  'В заказе',
                                                'url'   =>  Url::toRoute(['/goods/requests', 'act' => 'order'])
                                            ],[
                                                'label' =>  'На сайте',
                                                'url'   =>  Url::toRoute(['/goods/requests', 'act' => 'site'])
                                            ],
                                        ]
                                    ],
                                ],
                            ],
                            [
                                'label' =>  'Касса',
                                'url'   =>  Url::to('/kassa/index')
                            ],
                            [
                                'label' =>  'Руколдельницы',
                                'items' =>  [
                                    [
                                        'label' =>  'Рукодельницы',
                                        'url'   =>  '#'
                                    ],[
                                        'label' =>  'Типы аккаунтов',
                                        'url'   =>  '#'
                                    ],
                                ]
                            ],
                            [
                                'label' =>  'Баннеры',
                                'url'   =>  Url::toRoute('/banners/index')
                            ],
                            [
                                'label' =>  'Контроль заказа',
                                'url'   =>  Url::toRoute('/ordercontrol/index')
                            ],
                            [
                                'label' =>  'Возвраты',
                                'url'   =>  Url::to('/returns/index')
                            ],

                            [
                                'label' =>  'Промокоды',
                                'url'   =>  Url::to('/promocodes/index')
                            ],
                            [
                                'label' =>  'Ценовые правила',
                                'url'   =>  Url::to('/pricerules/index')
                            ],
                            [
                                'label' =>  'Отчёты',
                                'url'   =>  '#',
                                'items' =>  [
                                    [
                                        'label' =>  'Статистика (графики)',
                                        'url'   =>  Url::toRoute('/charts/index')
                                    ],
                                    [
                                        'label' =>  'Отключеные товары',
                                        'url'   =>  Url::toRoute('/charts/goods?mod=disabled')
                                    ],
                                    [
                                        'label' =>  'Включеные товары',
                                        'url'   =>  Url::toRoute('/charts/goods?mod=enabled')
                                    ],
                                    [
                                        'label' =>  'Отгруженые товары',
                                        'url'   =>  Url::toRoute('/charts/goods?mod=shipped')
                                    ],
                                    [
                                        'label' =>  'Подтверждённые заказы',
                                        'url'   =>  Url::toRoute('/charts/orders?mod=confirmed')
                                    ],
                                    [
                                        'label' =>  'Забраные Global Money',
                                        'url'   =>  Url::toRoute('/charts/takedglobalmoney')
                                    ],
                                    [
                                        'label' =>  'Продажи из магазина и самовывоз',
                                        'url'   =>  Url::toRoute('/charts/shopsales')
                                    ],
                                ]
                            ],
                            [
                                'label' =>  'Кабинет телефониста',
                                'url'   =>  '#'
                            ],
                        ]
                    ],
                    [
                        'label' =>  'Клиенты',
                        'url'   =>  Url::toRoute('/customers/index'),
                        'items' =>  [
                            [
                                'label' =>  'Обратная связь',
                                'url'   =>  '#',
                                'items' =>  [
                                    [
                                        'label' =>  'Отзывы',
                                        'url'   =>  Url::toRoute('/feedback/reviews')
                                    ],
                                    [
                                        'label' =>  'Вопросы',
                                        'url'   =>  Url::toRoute('/feedback/questions')
                                    ],
                                    [
                                        'label' =>  'Запросы на перезвон',
                                        'url'   =>  Url::toRoute('/feedback/requestcall')
                                    ],
                                    [
                                        'label' =>  'Жалобы',
                                        'url'   =>  Url::toRoute('/feedback/lament')
                                    ],
                                    [
                                        'label' =>  'Голосования',
                                        'url'   =>  Url::toRoute('/feedback/vote')
                                    ],
                                ]
                            ],
                            [
                                'label' =>  'Рассылка',
                                'url'   =>  '#'
                            ],
                        ]
                    ],
                    [
                        'label' =>  'Админчасть',
                        'url'   =>  '#',
                        'items' =>  [
                            [
                                'label' =>  'График дежурств',
                                'url'   =>  '#'
                            ],
                            [
                                'label' =>  'Виртуальные категории',
                                'url'   =>  '#'
                            ],
                            [
                                'label' =>  'Импорт excel',
                                'url'   =>  '#'
                            ],
                            [
                                'label' =>  'Пользователи',
                                'url'   =>  Url::toRoute('/users/index')
                            ],
                            [
                                'label' =>  'Способы оплаты',
                                'url'   =>  '#'
                            ],
                            [
                                'label' =>  'Шаблоны сообщений',
                                'url'   =>  '#'
                            ],
                            [
                                'label' =>  'Перевод',
                                'url'   =>  '#'
                            ],
                            [
                                'label' =>  'Подтверждение перевода',
                                'url'   =>  '#'
                            ],
                        ]
                    ],
                    [
                        'label' =>  'Блог',
                        'url'   =>  '#',
                        'items' =>  [
                            [
                                'label' =>  'Тэги',
                                'url'   =>  '#'
                            ],
                            [
                                'label' =>  'Категории',
                                'url'   =>  '#'
                            ],
                            [
                                'label' =>  'Статьи',
                                'url'   =>  Url::toRoute('/blog/index')
                            ],
                        ]
                    ],
                ],
            ],
            [
                'label'     =>  'Сообщения',
                'counter'   =>  '6'
            ],
            [
                'label'     =>  'Задания',
                'url'       =>  Url::toRoute('/tasks/index')
            ],
            /*[
                'label' =>  '<i class="glyphicon glyphicon-usd large" id="currency-icon" data-target="#currencyModal" data-toggle="modal"></i><span class="visible-xs-inline">&nbsp;Курс</span>'
            ],
            [
                'label' =>  'Оплат <span class="badge">0</span>'
            ],
            [
                'label' =>  'Товаров на заказ <span class="badge">0</span>'
            ],
            [
                'label' =>  '<i class="glyphicon glyphicon-envelope large"></i><span class="visible-lg-inline visible-xs-inline">&nbsp;Чат</span>',
                'url'   =>  '#'
            ],
            [
                'label' => 'Выйти<span class="visible-lg-inline visible-md-inline visible-xs-inline"> (' . Yii::$app->user->identity->name . ')</span>',
                'url' => ['/admin/logout'],
                'linkOptions' => ['data-method' => 'post'],
                'options'   =>  [
                    'class' =>  'pull-right'
                ]
            ]*/
        ],
    ]);
    ?>
    <style>
        .afterMenu{
            margin-top: -5px;
            margin-bottom: 20px;
            height: 30px;
            font-size: 12px;
            width: 100%;
            max-width: 1140px;
            white-space: nowrap;
        }

        .afterMenu > div{
            display: inline-block;
            min-width: 29%;
            max-width: 66%;
            line-height: 30px;
            color: #999;
        }

        .afterMenu .items-left{
            margin-right: 20px;
        }

        .afterMenu .items-left .btn-group{
            margin-right: 20px;
        }

        .afterMenu span{
            font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif;
        }

        .afterMenu .items-left{
            left: 0;
        }

        .afterMenu .items-center{
            margin: 0px auto;
        }

        .afterMenu .items-right{
            right: 0;
            text-align: right;
            margin-left: 20px;
        }

        .afterMenu .items-right button, .afterMenu .items-right > a{
            font-size: 12px;
            padding: 0; line-height: 30px;color: black;
            margin: 0 0 0 2px;
        }

        .rollback{
            position: fixed;
            padding-top: 110px;
            width: 80px;
            margin-top: -85px;
            height: 100vh;
            background: rgb(236,236,236);
            opacity: 0.4;
            text-align: center;
        }

        .rollback:hover{
            background: rgb(191, 191, 191);
            cursor: pointer;
        }

        @media only screen
        and (max-device-width: 1170px){
            .rollback{
                display: none;
            }
        }

        @media only screen
        and (max-device-width: 1600px){
            .rollback.orders{
                width: 30px !important;
            }
        }
    </style>

    <div class="rollback<?=$ordersPage ? ' orders' : ''?>" onclick="history.back()"><?=FA::icon('arrow-left')?></div>
    <div class="container"<?=$ordersPage == 'Заказы' ? ' style="max-width: 1300px; width: auto;"' : ''?>>
        <?=ServiceMenuWidget::widget([
            'showDateButtons'   =>  isset($this->params['showDateButtons']) ? $this->params['showDateButtons'] : false
        ])?>
        <?= Breadcrumbs::widget([
            'homeLink'  =>  ['label' => 'Главная', 'url' => Url::home()],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]);
        echo $content ?>
    </div>
    <?php Yamm::end(); ?>
</div>
<footer class="footer">
    <div class="container">
        <p class="pull-left">Сборка </p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
