<?php
use bobroid\y2sp\ScrollPager;
use frontend\helpers\PriceRuleHelper;
use yii\bootstrap\Html;
use yii\web\JsExpression;
use yii\widgets\ListView;

$helper = new PriceRuleHelper();

echo Html::tag('div',
    Html::tag('div',
        \frontend\widgets\ListGroupMenu::widget([
            'items'    => [
                [
                    'label' =>  'Личные данные',
                    'href'  =>  '/account'
                ],
                [
                    'label' =>  'Мои заказы',
                    'href'  =>  '/account/orders'
                ],
                [
                    'label' =>  'Моя скидка',
                    'href'  =>  '/account/discount'
                ],
                [
                    'label' =>  'Список желаний',
                    'href'  =>  '/account/wish-list'
                ],
                [
                    'label' =>  'Мои отзывы',
                    'href'  =>  '/account/reviews'
                ],
                [
                    'label' =>  'Возвраты',
                    'href'  =>  '/account/returns'
                ],
                [
                    'label' =>  'Ярмарка мастеров',
                    'href'  =>  '/account/yarmarka-masterov'
                ],
            ]
        ]),
        [
            'class' =>  'menu'
        ]).
    Html::tag('div',
        ListView::widget([
            'dataProvider'  =>  $items,
            'itemView'      =>  function($model) use (&$helper){
                $helper->recalc($model, true);

                return $this->render('../../../../views/site/_shop_item', [
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
                'noneLeftText'      =>  '',
                'paginationClass'   =>  'pagination',
                'paginationSelector'=>  'pagi',
                'triggerOffset'     =>  \Yii::$app->request->get('offset'),
                'eventOnPageChange' =>  new JsExpression('
                    function(offset){
                        if(params[\'offset\']){
                            offset > params[\'offset\'][0] ? params[\'offset\'][0] = offset : \'\';
                        }else{
                            params[\'offset\'] = [];
					        params[\'offset\'].push(offset);
                        }
                        $(\'.list-view .pagination .active ~ li:not(.next)\').each(
                            function(){
                                if(offset > 1){
                                    $(this).addClass(\'active\');
                                    offset--;
                                }
                            }
                        )
                        window.history.replaceState({}, document.title, buildLinkFromParams(false, false));
                        return false;
                    }
                ')
            ]
        ]),
        [
            'class' =>  'user-data-content'
        ]),
    [
        'class' =>  'content'
    ]);