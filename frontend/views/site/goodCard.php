<?php
use bobroid\remodal\Remodal;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use evgeniyrru\yii2slick\Slick;
use yii\web\JsExpression;

$link = '/tovar/'.$good->link.'-g'.$good->ID;

$reviewModal = new Remodal([
                                               'cancelButton'		=>	false,
                                               'confirmButton'		=>	false,
                                               'closeButton'		=>	false,
                                               'addRandomToID'		=>	false,
                                               'content'			=>	$this->render('goodCard/_write_review'),
                                               'id'				=>	'reviewModal',
                                           ]);

$js = <<<'JS'
(function(w,doc) {
    if (!w.__utlWdgt ) {
        w.__utlWdgt = true;
        var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
        s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
        s.src = ('https:' == w.location.protocol ? 'https' : 'http')  + '://w.uptolike.com/widgets/v1/uptolike.js';
        var h=d[g]('body')[0];
        h.appendChild(s);
    }})(window,document);
JS;

$this->registerJs($js);

\rmrevin\yii\fontawesome\AssetBundle::register($this);

$this->registerCssFile('/css/goodCard.css');
$this->registerCssFile('/css/img.css');
$this->registerCssFile('/css/base64.css');

$this->params['breadcrumbs'][] = [
    'label' =>  $good->Name
];

$captFlags = [];

$tabsItems = [
    [
        'label'     =>  \Yii::t('shop', 'Основное'),
        'content'   =>  $this->render('goodCard/_tabItem_main', [
            'good'  =>  $good
        ])
    ],
    [
        'label'     =>  \Yii::t('shop', 'Характеристики'),
        'content'   =>  $this->render('goodCard/_tabItem_characteristics', [
            'good'  =>  $good
        ])
    ],
    [
        'label'     =>  \Yii::t('shop', 'Отзывы'),
        'content'   =>  $this->render('goodCard/_tabItem_reviews', [
            'good'  =>  $good
        ])
    ]
];

if($good->video){
    $tabsItems[] = [
        'label'     =>  \Yii::t('shop', 'Видео'),
        'content'   =>  $this->render('goodCard/_tabItem_video', [
            'good'  =>  $good
        ])
    ];
}

if($good->isNew){
    $captFlags[] = '<div class="capt-flag bg-capt-green">'.\Yii::t('shop', 'Новинка').'</div>';
};

if($good->originalGood){
    $captFlags[] = '<div class="capt-flag bg-capt-purple">'.\Yii::t('shop', 'Оригинал').'</div>';
};

if(isset($good->PrOut3)){
    $captFlags[] = '<div class="capt-flag bg-capt-red">'.\Yii::t('shop', 'Распродажа').'</div>';
};

$items = [];
$itemsNav = [];

foreach($good->dopPhoto as $photo){
    $items[] = Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['img-path'].$photo->ico, ['width'=>'475px',
        'height'=>'355px']);
    $itemsNav[] = Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$photo->ico,
                           ['width'=>'105px', 'height'=>'80px']);
}

$this->title = $good->Name;

?>
<script type="text/javascript">(function(w,doc) {
        if (!w.__utlWdgt ) {
            w.__utlWdgt = true;
            var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
            s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
            s.src = ('https:' == w.location.protocol ? 'https' : 'http')  + '://w.uptolike.com/widgets/v1/uptolike.js';
            var h=d[g]('body')[0];
            h.appendChild(s);
        }})(window,document);
</script>
<div class="catalog">
    <div class="catalog-menu">
        <?=\yii\widgets\Breadcrumbs::widget([
            'activeItemTemplate'    =>  '<span class="item-name" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">{link}</span>',
            'itemTemplate'          =>  '
                <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">{link}</span>
                <span class="fa fa-long-arrow-right fa-fw"></span>
            ',
            'links'                 =>  $this->params['breadcrumbs']
        ])?>
    </div>
    <div class="goodsCard" itemscope itemtype="http://schema.org/Product">
        <div class="itemInfo">
            <div class="photo-and-order">
                <div class="itemPhotos">
                    <!--<img itemprop="image" data-modal-index="0"
                          src="<?=\Yii::$app->params['cdn-link']?>/img/catalog/sm/<?=$good->ico?>" width=""
                          height="" alt="<?=$good->Name?>">-->
                        <?=!empty($items) ? Slick::widget([
                            'containerOptions' => [
                                'id'    => 'sliderFor',
                                'class' => 'first'
                            ],
                            'items' =>  $items,
                            'clientOptions' => [
                                    'arrows'         => false,
                                    'fade'           => true,
                                    'slidesToShow'   => 1,
                                    'slidesToScroll' => 1,
                                    'asNavFor'       => '#sliderNav',
                                ]
                        ]) : '<img itemprop="image" data-modal-index="0" src="'.\Yii::$app->params['cdn-link']
                        .'/img/catalog/sm/'.$good->ico.'"
                        width="475px" height="355px" alt="'.$good->Name.'">',
                        !empty($itemsNav) ? Slick::widget([
                            'containerOptions' => [
                                'id'    => 'sliderNav',
                                'class' => 'second'
                            ],
                            'items' =>  $itemsNav,
                            'clientOptions' => [
                                    'arrows'         => false,
                                    'focusOnSelect'  => true,
                                    'infinite'       => true,
                                    'slidesToShow'   => 4,
                                    'slidesToScroll' => 1,
                                    'asNavFor'       => '#sliderFor',
                                    'cssEase'        => 'linear',
                            ]
                        ]) : ''?>

                    <?php /*
                        <?php
                        $good['PrOut3'] = $good['PriceOut3'] ? explode('.', (string)number_format((float)$good['PriceOut3'], 2, '.', '')) : false;
                        ?>

                        не знаю что это
                        */ ?>

                    <?=Html::tag('div', implode('', $captFlags), [
                        'class' =>  'capt-flags'
                    ])?>
                    <?php /*
                        <?php $dopPhotosSize = sizeof($good['dopPhoto']); if($dopPhotosSize >= 1){ ?>
                            <div class="gallery itemImageSlider">
                                <div class="galleryCenter">
                                    <div class="centerSlide">
                                        <div class="slides">
                                            <?php $i = 1; foreach($good['dopPhoto'] as $onePhoto){ ?>
                                                <div class="slide">
                                                    <img data-modal-index="<?=$i?>" src="<?=$GLOBALS['CDN_LINK']?>/img/catalog/sm/<?=$onePhoto?>" width="85" height="65" alt="Дополнительное фото">
                                                </div>
                                                <?php $i++; } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        а тут мы вроде-бы планировалили прицепить новый слайдер
                        */ ?>
                </div>
                <div class="about-order">
                    <div class="title">
                        <h1 itemprop="name"><?=$good->Name?></h1>
                    </div>
                    <div class="code-novelty">
                        <div class="code blue">
                            <?=\Yii::t('shop', 'Код')?>: <?=$good->Code?>
                        </div>
                        <div class="novelty">
                            НОВИНКА
                        </div>
                    </div>
                <!--<div class="itemPhotos">
                    <img itemprop="image" data-modal-index="0" src="<?=\Yii::$app->params['cdn-link']?>/img/catalog/sm/<?=$good->ico?>" width="288" height="214" alt="<?=$good->Name?>">

                    <?php /*
                    <?php
                    $good['PrOut3'] = $good['PriceOut3'] ? explode('.', (string)number_format((float)$good['PriceOut3'], 2, '.', '')) : false;
                    ?>

                    не знаю что это
                    */ ?>

                    <?/*=Html::tag('div', implode('', $captFlags), [
                        'class' =>  'capt-flags'
                    ])*/
                    ?>
                    <?php /*
                    <?php $dopPhotosSize = sizeof($good['dopPhoto']); if($dopPhotosSize >= 1){ ?>
                        <div class="gallery itemImageSlider">
                            <div class="galleryCenter">
                                <div class="centerSlide">
                                    <div class="slides">
                                        <?php $i = 1; foreach($good['dopPhoto'] as $onePhoto){ ?>
                                            <div class="slide">
                                                <img data-modal-index="<?=$i?>" src="<?=$GLOBALS['CDN_LINK']?>/img/catalog/sm/<?=$onePhoto?>" width="85" height="65" alt="Дополнительное фото">
                                            </div>
                                            <?php $i++; } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    а тут мы вроде-бы планировалили прицепить новый слайдер
                    */ ?>
                </div>-->
                <div class="itemContent" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <div class="pricelist">
                        <div class="pricelist-content">
                            <div class="pricelist-content-available">
                            <!-- 4 разных вида:
                            pricelist-content-discount
                            pricelist-content-available
                            pricelist-content-not-available
                            pricelist-content-vip
                            -->
                            <?=Html::tag(
                                'div',
                                ($good->show_img == 1 ? $good->count < 1 ? \Yii::t('shop', 'Под заказ.') : \Yii::t('shop', 'Есть в наличии') : \Yii::t('shop', 'Нет в наличии')),
                                [
                                    'class'     =>  'availability',
                                    'itemprop'  =>  'availability',
                                    'href'      =>  'http://schema.org/InStock'
                                ]
                            )?>
                            <div class="counterWrapper">
                                <div class="price">
                                     <span>
                                        <?=$good->wholesale_price?>
                                        <?=\Yii::$app->params['domainInfo']['currencyShortName']?>
                                     </span>
                                </div>
                                <div class="retail-price">
                                    <span>
                                        <?=\Yii::t('shop', 'розничная цена: {retailPrice} {currency}', [
                                            'retailPrice' => $good->retail_price,
                                            'currency'      => \Yii::$app->params['domainInfo']['currencyShortName']])
                                        ?>
                                    </span>
                                    <span class="question-round-button">
                                        ?
                                    </span>
                                </div>
                            </div>
                            <div class="counterWrapper-vip">
                                <div class="price">
                                    <span>
                                        <?=$good->wholesale_price?>
                                        <?=\Yii::$app->params['domainInfo']['currencyShortName']?>
                                    </span>
                                </div>
                                <div class="retail-price">
                                    <span>
                                        <?=\Yii::t('shop', 'опт: {wholesalePrice} {currency}   розница:
                                            {retailPrice} {currency}', [
                                                'retailPrice'   => $good->retail_price,
                                                'wholesalePrice'   => $good->wholesale_price,
                                                'currency'      => \Yii::$app->params['domainInfo']['currencyShortName']
                                            ])
                                        ?>
                                    </span>
                                    <span class="question-round-button">
                                            ?
                                    </span>
                                </div>
                            </div>
                            <div class="counterWrapper-discount">
                                <div class="retail-price">
                                    <span>
                                        старая цена:<i>

                                            <?=\Yii::$app->params['domainInfo']['currencyShortName']?>
                                                </i>
                                        <span class="question-round-button">?</span>
                                    </span>
                                </div>
                                <div class="price">
                                    <span><?=$good->wholesale_price?></span>
                                </div>
                                <span class="saving"><?=\Yii::t('shop', '(Экономия: {economy} {currency})', [
                                        'economy'       => $good->wholesale_real_price - $good->wholesale_price,
                                        'currency'      => \Yii::$app->params['domainInfo']['currencyShortName']
                                    ])?></span>
                                <!--<div class="counter">
                                    <a class="minus" data-itemId="<?//=$good->Code?>"></a>
                                    <input value="<?//=$good->inCart ? $good->inCart : '1'?>" readonly="readonly"
                                    name="count" class="count" type="text" data-itemId="<?//=$good->Code?>">
                                    <a class="plus" data-itemId="<?//=$good->Code?>" data-countInStore="
                                    <?//=($good->isUnlimited == '1' ? 1000 : $good->count)?>"></a>
                                </div>-->
                            </div>
                            <div class="progress">
                                <?php if($good->count < 1){ ?>
                                    <div class="deliv">
                                        <span>Доставка: 1 - 4 дня</span>
                                    </div>
                                <?php }else{ ?>
                                    <div class="how-much-left">
                                        <div class="how-much-left-text">
                                            <span>Остаток на складе:</span>
                                            <span class="how-much-left-text-sec">заканчиваеться</span>
                                        </div>
                                        <div class="progressBar">
                                            <?=Html::tag('span', '', [
                                                'class'         =>  'progressLine',
                                                'data-width'    =>  ($good->isUnlimited == '1' ? 100 : ($good->count < 20 ? $good->count * 5 : 100)).'%'
                                            ])?>
                                        </div>
                                        <span><?=($good->isUnlimited == '1' ? 100 : ($good->count < 20 ? $good->count * 5 : 100))?>%</span>

                                    </div>
                                        <?php } ?>
                                    <?php /*
                                <?php
                                $good['PrOut1'] = explode('.', (string)number_format((float)$good['PriceOut1'] * $_SESSION['domainInfo']['exchange'], 2, '.', ' '));
                                $good['PrOut1'][1] = $_SESSION['domainInfo']['coins'] == 1 ? '<sup>'.$good['PrOut1'][1].'</sup>' : '';
                                $good['PrOut2'] = explode('.', (string)number_format((float)$good['PriceOut2'] * $_SESSION['domainInfo']['exchange'], 2, '.', ' '));
                                $good['PrOut2'][1] = $_SESSION['domainInfo']['coins'] == 1 ? '<sup>'.$good['PrOut2'][1].'</sup>' : '';
                                $good['PrOut3'] = $good['PriceOut3'] ? explode('.', (string)number_format((float)$good['PriceOut3'] * $_SESSION['domainInfo']['exchange'], 2, '.', ' ')) : false;
                                if($good['PrOut3']){
                                    $good['PrOut3'][1] = $_SESSION['domainInfo']['coins'] == 1 ? '<sup>'.$good['PrOut3'][1].'</sup>' : '';
                                }
                                ?>
                                <div class="<?=($good['PrOut3'] ? 'new-' : '')?>opt semi-bold <?=($good['PrOut3'] ? 'red' : 'blue')?>">
                                    <span itemprop="price" content="<?=$good['PriceOut1']?>"><?=$good['PrOut1']['0']?></span><?=$good['PrOut1']['1']?><span class="uah" itemprop="priceCurrency" content="<?=$_SESSION['domainInfo']['currencyCode']?>"> <?=$_SESSION['domainInfo']['currencyShortName']?></span>
                                </div>
                                <?php if(!$good['onePrice'] || $good['PrOut3']){ ?>
                                    <div class="<?=($good['PrOut3'] ? 'old-' : 's')?>opt">
                                        <span><?=($good['PrOut3'] ? _("опт") : _("розница"))?> - <?=($good['PrOut3'] ? $good['PrOut3']['0'] : $good['PrOut2']['0'])?></span><?=($good['PrOut3'] ? $good['PrOut3']['1'] : $good['PrOut2']['1'])?><span> <?=$_SESSION['domainInfo']['currencyShortName']?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        */ ?>
                                    <?php
                                    $divContent = $good->canBuy ? Html::input('button', null, \Yii::t('shop',
                                        ($good->inCart ? 'В корзине!' : 'КУПИТЬ')), [
                                        'class'         =>  ($good->inCart ? 'green-button openCart' :
                                                'yellow-button buy')
                                            .' large-button',
                                        'data-itemId'   =>  $good->Code,
                                        'data-count'    =>  '1'
                                    ]) : \Yii::t('shop', 'Нет в наличии');

                                    echo Html::tag('div', $divContent, [
                                        'class' =>  $good->canBuy ? 'canBuy' : 'expectedArrival semi-bold'
                                    ])?>
                        <div class="reserve-button">
                                    <?php
                                    $divContent = $good->canBuy ? Html::input('button', null, \Yii::t('shop',
                                        ($good->inCart ? 'В корзине!' : 'Резервировать')), [
                                                                                  'class'         =>  ($good->inCart ? 'green-button openCart' :
                                                                                          'yellow-button buy')
                                                                                      .' large-button',
                                                                                  'data-itemId'   =>  $good->Code,
                                                                                  'data-count'    =>  '1'
                                                                              ]) : \Yii::t('shop', 'Нет в наличии');

                                    echo Html::tag('div', $divContent, [
                                        'class' =>  $good->canBuy ? 'canBuy' : 'expectedArrival semi-bold'
                                    ])?>
                        </div>
                        <div class="about-price">
                            <a class="reserve">Нашли дешевлее?</a>
                            <a class="about-price-available">Узнать о снижении цены</a>
                            <a class="about-price-not-available">Узнать когда появиться</a>
                            <a class="favorites">в избранное</a>
                        </div>
                    </div>
                    <div class="line-rating">
                        <div class="rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                           <!--<span class="currentRating" itemprop="ratingValue"><?=($good->rate ? $good->rate : 5)
                                ?></span>-->
                            <div class="shop-star<?=$good->rate == 5 ? ' current' : ''?>" itemprop="bestRating"
                                 title="<?=\Yii::t('shop', 'Отлично')?>" data-item="<?=$good->ID?>" data-rate="5">5</div>
                            <div class="shop-star<?=4 <= $good->rate && $good->rate < 5 ? ' current' : ''?>"
                                 title="<?=\Yii::t('shop', 'Хорошо')?>" data-item="<?=$good->ID?>" data-rate="4">4</div>
                            <div class="shop-star<?=3 <= $good->rate && $good->rate < 4 ? ' current' : ''?>"
                                 title="<?=\Yii::t('shop', 'Средне')?>" data-item="<?=$good->ID?>" data-rate="3">3</div>
                            <div class="shop-star<?=2 <= $good->rate && $good->rate < 3 ? ' current' : ''?>"
                                 title="<?=\Yii::t('shop', 'Приемлемо')?>" data-item="<?=$good->ID?>" data-rate="2">2</div>
                            <div class="shop-star<?=1 <= $good->rate && $good->rate < 2 ? ' current' : ''?>"
                                 itemprop="worstRating" title="<?=\Yii::t('shop', 'Плохо')?>" data-item="<?=$good->ID?>" data-rate="1">1</div>
                        </div>
                        <span class="link-hide blue shop-comment-empty" data-href="<?=$link?>#tab-reviews">
                            <?=Yii::t('shop', '{n, number} {n, plural, one{отзыв} few{отзыва} many{отзывов} other{отзывов}}', [
                                'n' =>  $good->reviewsCount
                            ])?>
                        </span>
                    </div>
                    <div class="line"></div>
                    <?php if($good->garantyShow == '1'){
                        if($good->anotherCurrencyPeg == '1'){
                            echo Html::tag('div', Html::tag('span', \Yii::t('shop', 'Внимание!'), [
                                'class' =>  'semi-bold blue'
                            ]).' '.\Yii::t('shop', 'Цена действительна при отправке или оплате заказа {date} до
                            23:59', [
                                   'date'  =>  date('d.m.Y')
                                ]), [
                                'class' =>  'underline'
                            ]);
                        }
                        if(strstr($category->Code, 'AAIABF')){ ?>
                            <div class="discount-text underline"><span class="semi-bold">Скидка 5%</span> при заказе от 5 тыс. грн.<span class="tooltip-question"></span>
                                <div class="tooltip">
                                    <div class="discountTooltip">При заказе товаров из раздела <span class="link-hide blue" data-href="<?=$_SESSION['linkLang']?>/salonam-krasoty/tehnika-dlya-salonov">«Техника для салонов»</span>.</div>
                                </div>
                            </div>
                        <?php }
                    } ?>
                    </div>
                            </div>
                        <div class="pricelist-warning">
                            <span>Внимание!</span>
                            Цена действительна при оплате заказа 03.11.14 до 21:00

                        </div>
                </div>
                <div class="itemDopInfo">
                    <?php if($good->garantyShow == '1'){ ?>
                        <div class="warranty"><img src="/template/img/warranty.png" alt="<?=\Yii::t('shop', 'гарантия')?>" width="82" height="66"><div class="semi-bold"><?=\Yii::t('shop', '12 месяцев гарантии')?></div><?=\Yii::t('shop', 'Обмен/возврат товара в течение 14 дней')?></div>
                    <?php } ?>
                    <div class="deliveryType">
                        <div class="minihead"><?=\Yii::t('shop', 'Доставка 2-4 дня')?><span class="tooltip-question"></span>
                            <div class="tooltip">
                                <div class="deliveryTooltip"><?=\Yii::t('shop', 'Доставка заказа по Украине осуществляется от 1 до 5 дней транспортной организацией')?> «<span class="link-hide" data-href="//novaposhta.ua/"><?=\Yii::t('shop', 'Новая Почта')?></span>».</div>
                            </div>
                        </div>
                        <div>• <?=\Yii::t('shop', 'самовывоз из нашего магазина')?></div>
                        <div>• <?=\Yii::t('shop', 'до склада Новой Почты')?></div>
                    </div>
                    <div class="payType">
                        <div class="minihead"><?=\Yii::t('shop', 'Оплата')?><span class="tooltip-question"></span>
                            <div class="tooltip">
                                <div class="payTooltip"><?=\Yii::t('shop', 'Возможна предоплата и оплата при получении.')?></div>
                            </div>
                        </div>
                        <span>• <?=\Yii::t('shop', 'Наличными')?>, <?=\Yii::t('shop', 'Безналичными')?></span>
                        <div>• Visa/MasterCard</div>
                    </div>
                    <div class="purchase-returns">
                        <div class="minihead"><?=\Yii::t('shop', '14 дней на возврат')?><span
                                class="tooltip-question"></span>
                            <div class="tooltip">
                                <div class="payTooltip"><?=\Yii::t('shop', 'Возможна предоплата и оплата при получении.')?></div>
                            </div>
                        </div>
                        <div><?=\Yii::t('shop', 'Возврат и обмен товара согласно')?></div>
                        <div><?=\Yii::t('shop', 'законодательству Украины')?></div>
                    </div>
                    <div class="guarantee">
                        <div class="minihead"><?=\Yii::t('shop', 'Гарантия 12 месяцев')?><span
                                class="tooltip-question"></span>
                            <div class="tooltip">
                                <div class="payTooltip"><?=\Yii::t('shop', 'Возможна предоплата и оплата при получении.')?></div>
                            </div>
                        </div>
                        <div>Официальная гарантия от производителя</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="socialItemInfo">
        <div class="soc-item-share">
            <div class="shareToFriends"><?=\Yii::t('shop', 'Рассказать друзьям')?></div>
            <div data-background-alpha="0.0" data-buttons-color="#FFFFFF" data-counter-background-color="#ffffff" data-share-counter-size="12" data-top-button="false" data-share-counter-type="disable" data-share-style="1" data-mode="share" data-like-text-enable="false" data-mobile-view="true" data-icon-color="#ffffff" data-orientation="horizontal" data-text-color="#000000" data-share-shape="round-rectangle" data-sn-ids="fb.vk.tw.ok.gp.em." data-share-size="20" data-background-color="#ffffff" data-preview-mobile="false" data-mobile-sn-ids="fb.vk.tw.wh.ok.vb." data-pid="1479727" data-counter-background-alpha="1.0" data-following-enable="false" data-exclude-show-more="true" data-selection-enable="false" class="uptolike-buttons" ></div>
        </div>
    </div>
    <div class="about-item">
        <?=\kartik\tabs\TabsX::widget([
                'items' =>  $tabsItems
        ])?>
    </div>

    <?php /*
    <div class="goodsCard" itemscope itemtype="http://schema.org/Product">


        <?php if(sizeof($similarGoods) > 0){ ?>
            <div class="recently-viewed">
                <div class="label semi-bold"><?=_("Похожие товары")?></div>
                <?php foreach($similarGoods as $oneItem){
                    $oneItem['PrOut1'] = explode('.', (string)number_format((float)$oneItem['PriceOut1'] * $_SESSION['domainInfo']['exchange'], 2, '.', ' '));
                    $oneItem['PrOut1'][1] = $_SESSION['domainInfo']['coins'] == 1 ? '<sup>'.$oneItem['PrOut1'][1].'</sup>' : '';
                    $oneItem['PrOut2'] = explode('.', (string)number_format((float)$oneItem['PriceOut2'] * $_SESSION['domainInfo']['exchange'], 2, '.', ' '));
                    $oneItem['PrOut2'][1] = $_SESSION['domainInfo']['coins'] == 1 ? '<sup>'.$oneItem['PrOut2'][1].'</sup>' : '';
                    $oneItem['PrOut3'] = $oneItem['PriceOut3'] ? explode('.', (string)number_format((float)$oneItem['PriceOut3'] * $_SESSION['domainInfo']['exchange'], 2, '.', ' ')) : false;
                    if($oneItem['PrOut3']){
                        $oneItem['PrOut3'][1] = $_SESSION['domainInfo']['coins'] == 1 ? '<sup>'.$oneItem['PrOut3'][1].'</sup>' : '';
                    }
                    ?>
                    <div class="hovered">
                        <div class="item">
                            <div class="inner">
                                <div class="title-block">
                                    <div class="title">
                                        <a class="blue" href="<?=$_SESSION['linkLang'].'/tovar/'.$oneItem['link'].'-g'.$oneItem['ID']?>" title="<?=$oneItem['Name']?>"><?=$oneItem['Name']?></a>
                                    </div>
                                </div>
                                <div class="openItem">
                                    <img class="link-hide" data-href="<?=$_SESSION['linkLang'].'/tovar/'.$oneItem['link'].'-g'.$oneItem['ID']?>" alt="<?=$oneItem['Name']?>" src="<?=$GLOBALS['CDN_LINK']?>/img/catalog/sm/<?=$oneItem['ico']?>" title="<?=$oneItem['Name']?> - оптовый интернет-магазин Krasota-Style" height="100" width="140">
                                    <?php if($oneItem['isNew']){ ?>
                                        <div class="new"></div>
                                    <?php } ?>
                                </div>
                                <div class="pricelist">
                                    <div>
                                        <div class="<?=($oneItem['PrOut3'] ? 'new-' : '')?>opt semi-bold <?=($oneItem['PrOut3'] ? 'red' : 'blue')?>">
                                            <span><?=$oneItem['PrOut1']['0']?></span><?=$oneItem['PrOut1']['1']?><span class="uah"> <?=$_SESSION['domainInfo']['currencyShortName']?></span>
                                        </div>
                                        <?php if(!$oneItem['onePrice'] || $oneItem['PrOut3']){ ?>
                                            <div class="<?=($oneItem['PrOut3'] ? 'old-' : 's')?>opt">
                                                <span><?=($oneItem['PrOut3'] ? _("опт") : _("розн."))?> - <?=($oneItem['PrOut3'] ? $oneItem['PrOut3']['0'] : $oneItem['PrOut2']['0'])?></span><?=($oneItem['PrOut3'] ? $oneItem['PrOut3']['1'] : $oneItem['PrOut2']['1'])?><span> <?=$_SESSION['domainInfo']['currencyShortName']?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div<?php if($oneItem['canBuy']){ ?> class="canBuy">
                                        <?php if($oneItem['inCart']){ ?>
                                            <input class="greenButton small-button openCart" value="<?=_("В корзине!")?>" type="button" data-itemId="<?=$good['Code']?>">
                                        <?php }else{
                                            $oneItem['inCart'] = '1'; ?>
                                            <input class="yellowButton small-button buy" value="<?=_("Купить!")?>" type="button" data-itemId="<?=$oneItem['Code']?>" data-count="1"><?php } ?>
                                        <?php }else{ ?> class="expectedArrival semi-bold">
                                            <?=_("Нет в наличии")?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="dopInfo">
                                <?php if($oneItem['canBuy']){ ?>
                                    <div class="counter">
                                        <a class="minus" data-itemId="<?=$oneItem['Code']?>"></a>
                                        <input value="<?=$oneItem['inCart']?>" readonly="readonly" name="count" class="count" type="text" data-itemId="<?=$oneItem['Code']?>">
                                        <a class="plus" data-itemId="<?=$oneItem['Code']?>" data-countInStore="<?=($oneItem['isUnlimited'] == '1' ? 1000 : $oneItem['Qtty'])?>"></a>
                                    </div>
                                <?php } ?>
                                <div class="progress"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if(sizeof($recentlyViews) > 0){ ?>
            <div class="recently-viewed">
                <div class="label semi-bold"><?=_("Недавно просмотренные")?></div>
                <?php foreach($recentlyViews as $oneItem){
                    $oneItem['PrOut1'] = explode('.', (string)number_format((float)$oneItem['PriceOut1'] * $_SESSION['domainInfo']['exchange'], 2, '.', ' '));
                    $oneItem['PrOut1'][1] = $_SESSION['domainInfo']['coins'] == 1 ? '<sup>'.$oneItem['PrOut1'][1].'</sup>' : '';
                    $oneItem['PrOut2'] = explode('.', (string)number_format((float)$oneItem['PriceOut2'] * $_SESSION['domainInfo']['exchange'], 2, '.', ' '));
                    $oneItem['PrOut2'][1] = $_SESSION['domainInfo']['coins'] == 1 ? '<sup>'.$oneItem['PrOut2'][1].'</sup>' : '';
                    $oneItem['PrOut3'] = $oneItem['PriceOut3'] ? explode('.', (string)number_format((float)$oneItem['PriceOut3'] * $_SESSION['domainInfo']['exchange'], 2, '.', ' ')) : false;
                    if($oneItem['PrOut3']){
                        $oneItem['PrOut3'][1] = $_SESSION['domainInfo']['coins'] == 1 ? '<sup>'.$oneItem['PrOut3'][1].'</sup>' : '';
                    }
                    ?>
                    <div class="hovered">
                        <div class="item">
                            <div class="inner">
                                <div class="title-block">
                                    <div class="title">
                                        <a class="blue" href="<?=$_SESSION['linkLang'].'/tovar/'.$oneItem['link'].'-g'.$oneItem['ID']?>" title="<?=$oneItem['Name']?>"><?=$oneItem['Name']?></a>
                                    </div>
                                </div>
                                <div class="openItem">
                                    <img class="link-hide" data-href="<?=$_SESSION['linkLang'].'/tovar/'.$oneItem['link'].'-g'.$oneItem['ID']?>" alt="<?=$oneItem['Name']?>" src="<?=$GLOBALS['CDN_LINK']?>/img/catalog/sm/<?=$oneItem['ico']?>" title="<?=$oneItem['Name']?> - оптовый интернет-магазин Krasota-Style" height="100" width="140">
                                    <?php if($oneItem['isNew']){ ?>
                                        <div class="new"></div>
                                    <?php } ?>
                                </div>
                                <div class="pricelist">
                                    <div>
                                        <div class="<?=($oneItem['PrOut3'] ? 'new-' : '')?>opt semi-bold <?=($oneItem['PrOut3'] ? 'red' : 'blue')?>">
                                            <span><?=$oneItem['PrOut1']['0']?></span><?=$oneItem['PrOut1']['1']?><span class="uah"> <?=$_SESSION['domainInfo']['currencyShortName']?></span>
                                        </div>
                                        <?php if(!$oneItem['onePrice'] || $oneItem['PrOut3']){ ?>
                                            <div class="<?=($oneItem['PrOut3'] ? 'old-' : 's')?>opt">
                                                <span><?=($oneItem['PrOut3'] ? _("опт") : _("розн."))?> - <?=($oneItem['PrOut3'] ? $oneItem['PrOut3']['0'] : $oneItem['PrOut2']['0'])?></span><?=($oneItem['PrOut3'] ? $oneItem['PrOut3']['1'] : $oneItem['PrOut2']['1'])?><span> <?=$_SESSION['domainInfo']['currencyShortName']?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div<?php if($oneItem['canBuy']){ ?> class="canBuy">
                                        <?php if($oneItem['inCart']){ ?>
                                            <input class="greenButton small-button openCart" value="<?=_("В корзине!")?>" type="button" data-itemId="<?=$good['Code']?>">
                                        <?php }else{
                                            $oneItem['inCart'] = '1'; ?>
                                            <input class="yellowButton small-button buy" value="<?=_("Купить!")?>" type="button" data-itemId="<?=$oneItem['Code']?>" data-count="1"><?php } ?>
                                        <?php }else{ ?> class="expectedArrival semi-bold">
                                            <?=_("Нет в наличии")?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="dopInfo">
                                <?php if($oneItem['canBuy']){ ?>
                                    <div class="counter">
                                        <a class="minus" data-itemId="<?=$oneItem['Code']?>"></a>
                                        <input value="<?=$oneItem['inCart']?>" readonly="readonly" name="count" class="count" type="text" data-itemId="<?=$oneItem['Code']?>">
                                        <a class="plus" data-itemId="<?=$oneItem['Code']?>" data-countInStore="<?=($oneItem['isUnlimited'] == '1' ? 1000 : $oneItem['Qtty'])?>"></a>
                                    </div>
                                <?php } ?>
                                <div class="progress"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
 */ ?>

        <!--<div class="SeoCity">
            <p><?=$good->Name.' '.\Yii::t('shop', 'на заказ по всей территории Украины: Киев, Харьков, Одесса, Львов, Днепропетровск, Донецк, Винница, Луганск, Луцк, Житомир, Запорожье, Ивано-Франковск, Николаев, Полтава, Ровно, Сумы, Тернополь, Ужгород, Херсон, Хмельницкий, Черкассы, Чернигов, Черновцы. Самовывоз товара со склада или доставка "Новой почтой".')?></p>
        </div>-->
    </div>
</div>
    </div>

