<?php
use kartik\dropdown\DropdownX;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>
<?=\backend\widgets\SmartFiltersWidget::widget([
    'items' =>  [
        [
            'label'         =>  'Всего товаров: ',
            'counterValue'  =>  $goodsCount['all']['enabled'] + $goodsCount['all']['disabled'],
            'labelClass'    =>  'label-info',
            'filter'        =>  ''
        ],
        [
            'label'         =>  'Отключеных: ',
            'counterValue'  =>  $goodsCount['all']['disabled'],
            'labelClass'    =>  'label-danger',
            'filter'        =>  'disabled'
        ],
        [
            'label'         =>  'Включеных: ',
            'counterValue'  =>  $goodsCount['all']['enabled'],
            'labelClass'    =>  'label-success',
            'filter'        =>  'enabled'
        ],
        [
            'label'         =>  'Другие',
            'labelClass'    =>  'label-success',
            'items'         =>  [
                [
                    'label'         =>  'Без фотографий',
                    'filter'        =>  'withoutPhoto'
                ],
                [
                    'label'         =>  'Без аттрибутов',
                    'filter'        =>  'withoutAttributes'
                ],
                [
                    'label'         =>  'На распродаже',
                    'filter'        =>  'onSale'
                ],
            ]
        ],
    ]
])?>
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