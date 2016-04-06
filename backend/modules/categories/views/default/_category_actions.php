<?php
use kartik\dropdown\DropdownX;
use yii\helpers\Url;

?>
<div class="dropdown categoryActions">
    <div class="btn-group" role="group" aria-label="Действия">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-expanded="true">
            Действия с категорией <span class="caret"></span>
        </button>
        <?=DropdownX::widget([
            'items' =>  [
                [
                    'label'     =>  'Просмотреть',
                    'url'       =>  Url::toRoute('view/'.$nowCategory->ID)
                ],
                [
                    'label'     =>  'Просмотреть на сайте',
                    'url'       =>  'https://krasota-style.com.ua/'.$nowCategory->link
                ],
                [
                    'label'     =>  'Редактировать',
                    'url'       =>  Url::toRoute(['view/'.$nowCategory->ID, 'act' => 'edit'])
                ],
                [
                    'label'     =>  'Добавить',
                    'items'     =>  [
                        [
                            'label'     =>  'Товар',
                            'url'       =>  Url::toRoute(['/good/add', 'category' => $nowCategory->ID])
                        ],
                        [
                            'label'     =>  'Несколько товаров',
                            'url'       =>  Url::toRoute(['/good/add', 'category' => $nowCategory->ID, 'mode' => 'lot'])
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
                    'label' =>  $nowCategory->enabled == "1" ? "Выключить" : "Включить",
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