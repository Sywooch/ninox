<?php

use frontend\helpers\PriceRuleHelper;

$this->title = '';
\Yii::$app->params['breadcrumbs'][] = [
    'label' =>  $category->Name
];

$helper = new PriceRuleHelper();

?>
<div class="content" style="margin-top: 100px;">
    <div class="contentCenter">
        <div class="leftMenu">
            <span class="catTitle">
                <?=''//UpLeftMenuBanners?>
                <a href="/<?=$category->link?>" title="<?=$category->Name?>"><?=$category->Name?></a>
                <?=''//Filters?>
                <?=''//LeftMenu?>
                <?=''//LeftMenuBanners?>
            </span>
        </div>
        <div class="catalog">
            <?=\yii\widgets\Breadcrumbs::widget([
                'activeItemTemplate'    =>  '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">{link}</span>',
                'itemTemplate'  =>  '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">{link}</span><span class="arrowBreadcrumbs"></span>',
                'links'         =>  \Yii::$app->params['breadcrumbs']
            ])?>
            <div class="label">
                <a href = "<?=''//$_SESSION['linkRoot']?>">
                    <div class="prevMobile">
                        <p><?=\Yii::t('shop', 'Назад')?></p>
                    </div>
                </a>
                <h1><?php
                    //Я честно не хотел этого делать - мне подставили пистолет к виску, и мне пришлось...
                    //Пля, Дорогие Бижутерия... я со смеху чуть не помер, лол xDD
                    /*
                    if($nowOrder != 'date'){
                        if($_SESSION['lang'] != 'uk'){
                            switch($nowOrder){
                                case 'asc':
                                    $h1 = $cat['h1asc'] == '' ? 'Дешёвые '.$cat['Name'] : $cat['h1asc'];
                                    break;
                                case 'desc':
                                    $h1 = $cat['h1desc'] == '' ? 'Дорогие '.$cat['Name'] : $cat['h1desc'];
                                    break;
                                case 'novinki':
                                    $h1 = $cat['h1new'] == '' ? 'Новинки '.($cat['catNameVinitelny2'] == '' ? $cat['Name'] : $cat['catNameVinitelny2']) : $cat['h1new'];
                                    break;
                            }
                        }else{
                            switch($nowOrder){
                                case 'asc':
                                    $h1 = $cat['h1asc'] == '' ? 'Дешеві '.$cat['Name'] : $cat['h1asc'];
                                    break;
                                case 'desc':
                                    $h1 = $cat['h1desc'] == '' ? 'Дорогі '.$cat['Name'] : $cat['h1desc'];
                                    break;
                                case 'novinki':
                                    $h1 = $cat['h1new'] == '' ? 'Новинки '.($cat['catNameVinitelny2'] == '' ? $cat['Name'] : $cat['catNameVinitelny2']) : $cat['h1new'];
                                    break;
                            }
                        }
                    }else{
                        $h1 = $cat['h1'] == '' ? $cat['Name'] : $cat['h1'];
                    }
                    echo $h1;*/
                    ?>
                </h1>
                <div class="goodsCountLabel">
                    <span>
                        <?=\Yii::t('shop', '{n, number} {n, plural, one{товар} few{товара} many{товаров} other{товар}}', ['n' => $category->goodsCount(true)])?>
                    </span>
                </div>
            </div>
            <div class="subCategories">

            </div>
            <?=\yii\widgets\ListView::widget([
                'dataProvider'  =>  $goods,
                'itemView'      =>  function($model, $param2, $param3, $widget) use (&$helper){$helper->recalc($model, true);
                    return $this->render('_shop_good', [
                        'model' =>  $model
                    ]);
                },
	            'itemOptions'   =>  [
		            'class'     =>  'hovered'
	            ],
                'pager'         =>  [
                    'class' =>  \common\components\ShopPager::className()
                ]
            ])?>
            <div class="categoryDescription"><?php if($showText){ echo htmlspecialchars_decode($category->text2); } ?>
                <div class="SeoCity">
                    <p><?=$category->Name.' '.\Yii::t('shop', 'с доставкой в Киев, Харьков, Одессу, Львов, Днепропетровск, Донецк, Винницу, Луганск, Луцк, Житомир, Запорожье, Ивано-Франковск, Николаев, Полтаву, Ровно, Сумы, Тернополь, Ужгород, Херсон, Хмельницкий, Черкассы, Чернигов, Черновцы.')?></p>
                </div>
            </div>
        </div>
    </div>
</div>