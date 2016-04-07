<?php

use frontend\helpers\PriceRuleHelper;
use frontend\widgets\Breadcrumbs;
use kop\y2sp\ScrollPager;
use yii\bootstrap\Html;
use yii\widgets\ListView;

$this->title = $category->Name;

$helper = new PriceRuleHelper();

\yii\widgets\Pjax::begin([
    'linkSelector'  =>  '.sub-categories li > a, .breadcrumb li > a'
]);

echo Html::tag('div',
    Html::tag('div',
        Html::tag('span', $category->Name, ['class' => 'category-title']).
        $this->render('_category/_category_filters'),
        ['class' => 'left-menu']
    ).
    ListView::widget([
        'dataProvider'  =>  $goods,
        'itemView'      =>  function($model) use (&$helper){
            $helper->recalc($model, true);

            return $this->render('_shop_item', [
                'model' =>  $model
            ]);
        },
        'summary'       =>  Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]).
            Html::tag('div',
                Html::tag('h1', $category->Name).
                Html::tag('span',
                    \Yii::t('shop',
                        '{n, number} {n, plural, one{товар} few{товара} many{товаров} other{товар}} в категории',
                        ['n' => $category->goodsCount(true)]
                    ),
                    ['class' => 'category-items-count']),
                ['class' => 'category-label']
            ).
            Html::ul($category->subCategories, [
                'item'      =>  function($item){
                    return Html::tag('li', Html::a($item->Name, '/'.$item->link));
                },
                'class'     =>  'sub-categories'
            ]),
        'layout'        => '{summary}'.
            Html::tag('div', '{items}', ['class' => 'items-grid clear-fix']).
            Html::tag('div', '{pager}', ['class' => 'pagination-wrapper']).
            Html::tag('div',
                htmlspecialchars_decode($category->text2).
                Html::tag('div',
                    Html::tag('p',
                        $category->Name.
                        ' '.
                        \Yii::t('shop',
                            'с доставкой в Киев, Харьков, Одессу, Львов, Днепропетровск, Донецк, Винницу, Луганск,
                            Луцк, Житомир, Запорожье, Ивано-Франковск, Николаев, Полтаву, Ровно, Сумы, Тернополь,
                            Ужгород, Херсон, Хмельницкий, Черкассы, Чернигов, Черновцы.')
                    ),
                    ['class' => 'seo-city']),
                ['class' => 'category-description']),
        'itemOptions'   =>  [
            'class'     =>  'hovered'
        ],
        'pager'         =>  [
            'class' =>  \common\components\ShopPager::className()
        ]
        /*'pager' =>  [
            'class'             =>  ScrollPager::className(),
            'container'         =>  '.list-view div.items-grid',
            'item'              =>  'div.hovered',
        ]*/
    ]),
    ['class' => $category->viewFile.' clear-fix']
);

\yii\widgets\Pjax::end();