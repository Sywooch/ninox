<?php
use kartik\dropdown\DropdownX;
use yii\bootstrap\Html;
use yii\helpers\Url;

$sf = \Yii::$app->request->get("smartFilter");
?>
<ul class="nav nav-pills">
    <?=Html::tag('li',
            Html::a('Всего товаров: '.Html::tag('span', ($goodsCount['all']['enabled'] + $goodsCount['all']['disabled']), ['class'=>'label label-info']), Url::toRoute(['/categories', 'category' => $nowCategory->Code, 'smartFilter' => ''])),
            [
                'role'      =>  'presentation',
                'class'     =>  $sf == '' ? 'active' : ''
            ])?>
        <?=Html::tag('li',
            Html::a('Выключено: '.Html::tag('span', ($goodsCount['all']['disabled']), ['class'=>'label label-danger']), Url::toRoute(['/categories', 'category' => $nowCategory->Code, 'smartFilter' => 'disabled'])),
            [
                'role'      =>  'presentation',
                'class'     =>  $sf == 'disabled' ? 'active' : ''
            ])?>
        <?=Html::tag('li',
            Html::a('Включено: '.Html::tag('span', ($goodsCount['all']['enabled']), ['class'=>'label label-success']), Url::toRoute(['/categories', 'category' => $nowCategory->Code, 'smartFilter' => 'enabled'])),
            [
                'role'      =>  'presentation',
                'class'     =>  $sf == 'enabled' ? 'active' : ''
            ])?>
</ul>
<br style="margin-bottom: 0;">
<div class="clearfix"></div>
<div class="dropdown categoryActions">
    <div class="btn-group" role="group" aria-label="Действия">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-expanded="true">
            Действия с категорией <span class="caret"></span>
        </button>
        <?=DropdownX::widget([
            'items' =>  [
                [
                    'label'     =>  'Просмотреть',
                    'url'       =>  Url::toRoute('show/'.$nowCategory->ID)
                ],
                [
                    'label'     =>  'Просмотреть на сайте',
                    'url'       =>  'https://krasota-style.com.ua/'.$nowCategory->link
                ],
                [
                    'label'     =>  'Редактировать',
                    'url'       =>  Url::toRoute(['show/'.$nowCategory->ID, 'act' => 'edit'])
                ],
                [
                    'label'     =>  'Добавить',
                    'items'     =>  [
                        [
                            'label'     =>  'Товар',
                            'url'       =>  Url::toRoute(['/good/addgood', 'category' => $nowCategory->ID])
                        ],
                        [
                            'label'     =>  'Несколько товаров',
                            'url'       =>  Url::toRoute(['/good/addgood', 'category' => $nowCategory->ID, 'mode' => 'lot'])
                        ],
                        '<li class="divider"></li>',
                        [
                            'label'     =>  'Категорию',
                            'url'       =>  Url::to(['add', 'parentCategory' => $nowCategory->ID])
                        ],
                    ]
                ],
                '<li class="divider"></li>',
                [
                    'label' =>  $nowCategory->menu_show == "1" ? "Выключить" : "Включить",
                    'options'   =>  [
                        'class' =>  'shutdown',
                        'data-attribute-categoryID' =>  $nowCategory->ID
                    ],
                    'url'   =>  '#'
                ],
                [
                    'label' =>  $nowCategory->canBuy == "1" ? "Не продавать" : "Продавать",
                    'options'   =>  [
                        'class' =>  'canBuy',
                        'data-attribute-categoryID' =>  $nowCategory->ID
                    ],
                    'url'   =>  '#'
                ]

            ]
        ])?>
    </div>
</div>
<br>