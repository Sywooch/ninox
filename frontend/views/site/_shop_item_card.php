<?php
use bobroid\remodal\Remodal;
use common\helpers\Formatter;
use frontend\widgets\Breadcrumbs;
use kartik\tabs\TabsX;
use yii\helpers\Html;
use evgeniyrru\yii2slick\Slick;


$this->title = $good->metaTitle;
$this->registerMetaTag(['name' => 'description', 'content' => $good->metaDescription], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $good->metaKeywords], 'keywords');

$link = '/tovar/'.$good->link.'-g'.$good->ID;

$reviewModal = new Remodal([
    'cancelButton'		=>	false,
    'confirmButton'		=>	false,
    'closeButton'		=>	false,
    'addRandomToID'		=>	false,
    'content'			=>	$this->render('_shop_item/_item_tabs/_write_review'),
    'id'				=>	'reviewModal',
]);



/*$js = <<<'JS'
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

$this->registerJs($js);*/

$this->registerCssFile('/css/img.css');

$tabsItems = [
    [
        'label'     =>  \Yii::t('shop', 'Основное'),
        'content'   =>  $this->render('_shop_item/_item_tabs/_tabItem_main', [
            'good'  =>  $good
        ])
    ],
    [
        'label'     =>  \Yii::t('shop', 'Характеристики'),
        'content'   =>  $this->render('_shop_item/_item_tabs/_tabItem_characteristics', [
            'good'  =>  $good
        ])
    ],
    [
        'label'     =>  \Yii::t('shop', 'Отзывы'),
        'content'   =>  $this->render('_shop_item/_item_tabs/_tabItem_reviews', [
            'good'  =>  $good
        ])
    ]
];

if($good->videos){
    $tabsItems[] = [
        'label'     =>  \Yii::t('shop', 'Видео'),
        'content'   =>  $this->render('_shop_item/_item_tabs/_tabItem_video', [
            'videos'  =>  $good->videos
        ]),
        'options'   =>  [
            'id'   =>  'tab-video',
        ]
    ];
}

$items = [];
$itemsNav = [];
$itemsModal = [];

foreach($good->photos as $photo){
    $items[] = Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['img-path'].$photo->ico, [
        'width'=>'475px',
        'height'=>'355px',
        'onerror' => "this.src='".\Yii::$app->params['noimage']."';"
    ]);

    $itemsModal[] = Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['img-path'].$photo->ico, [
        'onerror' => "this.src='".\Yii::$app->params['noimage']."';"
    ]);

    $itemsNav[] = Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['small-img-path'].$photo->ico, [
        'width'=>'105px',
        'height'=>'80px',
        'onerror' => "this.src='".\Yii::$app->params['noimage']."';"
    ]);
}

$imgModal = new \bobroid\remodal\Remodal([
    'cancelButton'		=>	false,
    'confirmButton'		=>	false,
    'closeButton'		=>	true,
    'addRandomToID'		=>	false,
    'content'			=>
        (empty($items) ? Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['img-path'].$good->photo,
            [
                'itemprop' => 'image',
                'data-modal-index'  =>  0,
                'width' =>  '475px',
                'height'=>  '355px',
                /*'alt'   =>  $good->Name,*/
                'onerror' => "this.src='".\Yii::$app->params['noimage']."';"
            ]) :
            Slick::widget([
                'containerOptions' => [
                    'id'    => 'modalSliderFor',
                    'class' => 'first-modal'
                ],
                'items' =>  $itemsModal,
            ])
        ).
        (sizeof($itemsNav) > 1 ? Slick::widget([
            'containerOptions' => [
                'id'    => 'modalSliderNav',
                'class' => 'second-modal'
            ],
            'items' =>  $itemsNav,
        ]) : ''),
    'id'				=>	'imgModal',
    'options'			=>  [
        'class'			=>  'img-modal'
    ],
    'events'			=>	[
        'opening'   =>	new \yii\web\JsExpression("$('#modalSliderNav').slick('unslick').slick({
            arrows: true,
            focusOnSelect: true,
            infinite: true,
            slidesToShow: 8,
            slidesToScroll: 1,
            asNavFor: '#modalSliderFor',
            cssEase: 'linear',
        });
        $('#modalSliderFor').slick('unslick').slick({
            arrows: true,
            fade: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            asNavFor: '#sliderFor',
        });"),
    ],
]);
?>
<div class="item item-card" itemscope itemtype="http://schema.org/Product">
    <div class="item-main clear-fix">
        <div class="item-photos">
            <?=(empty($items) ? Html::img(\Yii::$app->params['cdn-link'].\Yii::$app->params['img-path'].$good->photo,
                [
                    'itemprop' => 'image',
                    'data-modal-index'  =>  0,
                    'width' =>  '475px',
                    'height'=>  '355px',
                    /*'alt'   =>  $good->Name,*/
                    'onerror' => "this.src='".\Yii::$app->params['noimage']."';"
                ]) :
                Html::a(Slick::widget([
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
                        'asNavFor'       => '',
                    ]
                ]), '#imgModal')
            ).
            (sizeof($itemsNav) > 1 ? Slick::widget([
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
            ]) : '')
            ?>
        </div>
        <div class="item-info">
            <h1 class="title" itemprop="name"><?=$good->Name?></h1>
            <div class="code blue"><?=\Yii::t('shop', 'Код:').$good->Code?></div>
            <div class="item-offer" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <?=$this->render('_shop_item/_main_info', ['good' => $good])?>
                <div class="item-dop-info">
                    <div class="delivery-type">
                        <div class="minihead"><?=\Yii::t('shop', 'Доставка 2-4 дня')?></div>
                        <div>• <?=\Yii::t('shop', 'Самовывоз из нашего магазина')?></div>
                        <div>• <?=\Yii::t('shop', 'До склада Новой Почты')?></div>
                        <div>• <?=\Yii::t('shop', 'Курьером Новая Почта')?></div>
                    </div>
                    <div class="pay-type">
                        <div class="minihead"><?=\Yii::t('shop', 'Оплата')?></div>
                        <div>• <?=\Yii::t('shop', 'Наличными')?>, <?=\Yii::t('shop', 'Безналичными')?></div>
                        <div>• Visa/MasterCard</div>
                    </div>
                    <div class="purchase-returns">
                        <div class="minihead"><?=\Yii::t('shop', '14 дней на возврат')?></div>
                        <div><?=\Yii::t('shop', 'Возврат и обмен товара согласно')?></div>
                        <div><?=\Yii::t('shop', 'законодательству Украины')?></div>
                    </div>
                    <?php if($good->garantyShow == '1'){
                        echo Html::tag('div',
                            Html::tag('div', \Yii::t('shop', 'Гарантия 12 месяцев'),
                                [
                                    'class' => 'minihead'
                                ]
                            ).
                            Html::tag('div', \Yii::t('shop', 'Официальная гарантия от производителя')),
                            [
                                'class' =>  'guarantee'
                            ]
                        );
                    } ?>
                </div>
            </div>
        </div>
    </div>
<!--    <div class="soc-item-share">
        <span class="share-to-friends"><?/*=\Yii::t('shop', 'Рассказать друзьям')*/?></span>
        <span data-background-alpha="0.0" data-buttons-color="#FFFFFF" data-counter-background-color="#ffffff" data-share-counter-size="12" data-top-button="false" data-share-counter-type="disable" data-share-style="1" data-mode="share" data-like-text-enable="false" data-mobile-view="true" data-icon-color="#ffffff" data-orientation="horizontal" data-text-color="#000000" data-share-shape="round-rectangle" data-sn-ids="fb.vk.tw.ok.gp.em." data-share-size="20" data-background-color="#ffffff" data-preview-mobile="false" data-mobile-sn-ids="fb.vk.tw.wh.ok.vb." data-pid="1479727" data-counter-background-alpha="1.0" data-following-enable="false" data-exclude-show-more="true" data-selection-enable="false" class="uptolike-buttons"></span>
    </div>-->
    <div class="about-item">
        <?=TabsX::widget([
            'enableStickyTabs'  =>  true,
            'items'             =>  $tabsItems
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
    <?=$imgModal->renderModal()?>
</div>

