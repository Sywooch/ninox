<?php

use bobroid\y2sp\ScrollPager;
use frontend\helpers\PriceRuleHelper;
use frontend\widgets\Breadcrumbs;
use yii\bootstrap\Html;
use yii\widgets\ListView;

$this->title = $category->metaTitle;
$this->registerMetaTag(['name' => 'description', 'content' => $category->metaDescription], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $category->metaKeywords], 'keywords');

$helper = new PriceRuleHelper();

$js = <<<'JS'
    $('body').on('change', '.filter-rows input[type=checkbox]', function(){
        updateFilter(this);
    });

JS;

$this->registerJS($js);

\yii\widgets\Pjax::begin([
    'id'            =>  'pjax-category',
    'linkSelector'  =>  '.sub-categories li > a, .breadcrumb li > a',
    'timeout'       =>  '5000'
]);

echo Html::tag('div',
    $this->render('_category/_category_filters', [
        'filters'   =>  $category->filters,
        'min'       =>  $category->minPrice,
        'max'       =>  $category->maxPrice,
        'from'      =>  \Yii::$app->request->get('minPrice') ? \Yii::$app->request->get('minPrice') : $category->minPrice,
        'to'        =>  \Yii::$app->request->get('maxPrice') ? \Yii::$app->request->get('maxPrice') : $category->maxPrice,
    ]).
    Html::tag('div',
        Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]).
        Html::tag('div',
            Html::tag('h1', $category->Name).
            Html::tag('span',
                \Yii::t('shop',
                    '{n, number} {n, plural, one{товар} few{товара} many{товаров} other{товар}} в категории',
                    ['n' => $items->getTotalCount()]
                ),
                ['class' => 'category-items-count']),
            ['class' => 'category-label']
        ).
        Html::ul($category->subCategories, [
            'item'      =>  function($item){
                return Html::tag('li', Html::a($item->Name, '/'.$item->link));
            },
            'class'     =>  'sub-categories'
        ]).
        \kartik\select2\Select2::widget([
            'data'          => [
                'date'      =>  \Yii::t('shop', 'По рейтингу'),
                'asc'       =>  \Yii::t('shop', 'По возрастанию цены'),
                'desc'      =>  \Yii::t('shop', 'По убыванию цены'),
                'novinki'   =>  \Yii::t('shop', 'По новизне'),
            ],
            'name'          =>  'Sorting',
            'hideSearch'    => true,
            'value'         =>  \Yii::$app->request->get('order'),
            'language'      => \Yii::$app->language,
            'pluginOptions' =>   [
                'width' =>  '250px',
            ],
            'pluginEvents'  => [
                'change'    =>  'function(data){
                    window.history.replaceState({}, document.title, buildLinkFromParams(\'order\', data.target.value));
                    $.pjax.reload({container: \'#pjax-category\'});
                }'
            ],
        ]).
        ListView::widget([
            'dataProvider'  =>  $items,
            'itemView'      =>  function($model) use (&$helper){
                $helper->recalc($model, true);

                return $this->render('_shop_item', [
                    'model' =>  $model
                ]);
            },
            'layout'        =>
                Html::tag('div', '{items}', ['class' => 'items-grid clear-fix']).
                Html::tag('div', '{pager}', ['class' => 'pagination-wrapper']),
            'itemOptions'   =>  [
                'class'     =>  'hovered'
            ],
            'pager' =>  [
                'class'             =>  ScrollPager::className(),
                'item'              =>  '.hovered',
                'paginationClass'   =>  'pagination',
                'paginationSelector'=>  'pagi',
                'triggerOffset'     =>  \Yii::$app->request->get('offset'),
                'eventOnPageChange' =>  new \Yii\web\JsExpression('
                    function(offset){
                        if(params[\'offset\']){
                            offset > params[\'offset\'][0] ? params[\'offset\'][0] = offset : \'\';
                        }else{
                            params[\'offset\'] = [];
					        params[\'offset\'].push(offset);
                        }
                        window.history.replaceState({}, document.title, buildLinkFromParams(false, false));
                        return false;
                    }
                ')
            ]
        ]).
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
        ['class' => 'content']),
    ['class' => $category->viewFile.' clear-fix']
);

\yii\widgets\Pjax::end();