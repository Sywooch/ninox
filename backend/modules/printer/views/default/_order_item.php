<?php


use yii\bootstrap\Html;

echo Html::tag('td',
    Html::tag('li',
        Html::tag('div',
            Html::tag('div',
                Html::tag('table',
                    Html::tag('tr',
                        Html::tag('td',
                            Html::tag('span',
                                $counter.'. '.$item->name,
                                [
                                    'class' =>  'tov_title'
                                ]
                            ),
                            [
                                'class'     =>  'tov_name',
                                'colspan'   =>  2
                            ]
                        )
                    ).
                    Html::tag('tr',
                        Html::tag('td',
                            Html::tag('div',
                                Html::tag('span',
                                    Html::img(\Yii::$app->params['cdn-link'].'/img/catalog/'.$item->photo,
                                        [
                                            'alt'   =>  $item->name,
                                        ]
                                    ),
                                    [
                                        'class'     =>  'fancybox',
                                        'data-rel'  =>  'tovar',
                                        'data-title'=>  $item->name
                                    ]
                                ),
                                [
                                    'class' =>  'tovars_img2',
                                    'style' =>  'display: block;'
                                ]
                            ),
                            [
                                'colspan'   =>  2,
                            ]
                        ).
                        Html::tag('td',
                            "&nbsp;&nbsp;&nbsp;Код: {$item->code}".
                            Html::tag('br').
                            Html::tag('br').
                            "&nbsp;&nbsp;&nbsp;".
                            $item->price.' грн.&nbsp;'.
                            Html::tag('span', $item->count.' шт.').
                            Html::tag('br').
                            Html::tag('br').
                            Html::img('/img/zamena.png'),
                            [
                                'width' =>  '130'
                            ]
                        )
                    )
                ),
                [
                    'class' =>  'tovars_top2'
                ]
            ),
            [
                'class' =>  'tovars2'
            ]
        )
    ),
    [
    'width' =>  '50%'
]);