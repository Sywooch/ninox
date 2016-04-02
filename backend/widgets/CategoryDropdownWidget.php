<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 12.10.15
 * Time: 17:50
 */

namespace backend\widgets;

use kartik\dropdown\DropdownX;
use yii\base\Widget;
use yii\helpers\Url;

class CategoryDropdownWidget extends Widget{

    public $category;

    public function run(){
        return '<div class="dropdown pull-right" style="margin-left: 5px; margin-top: -8px; display: inline-block;">
    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-expanded="true">
        <span class="glyphicon glyphicon-option-horizontal large"></span>
        <span class="caret"></span>
    </button>'.DropdownX::widget([
            'options'   =>  [
                'class' =>  'categoryActions'
            ],
            'items' =>  [
                [
                    'label'     =>  'Просмотреть',
                    'url'       =>  Url::toRoute(['view/'.$this->category->ID])
                ],
                [
                    'label'     =>  'Просмотреть на сайте',
                    'url'       =>  'https://krasota-style.com.ua/'.$this->category->link
                ],
                [
                    'label'     =>  'Редактировать',
                    'url'       =>  Url::toRoute(['view/'.$this->category->ID, 'act' => 'edit'])
                ],
                [
                    'label'     =>  'Товары этой категории',
                    'url'       =>  Url::toRoute(['/goods', 'category' => $this->category->Code])
                ],
                [
                    'label'     =>  'Добавить',
                    'items'     =>  [
                        [
                            'label'     =>  'Товар',
                            'url'       =>  Url::toRoute(['/goods/add', 'category' => $this->category->ID])
                        ],
                        [
                            'label'     =>  'Несколько товаров',
                            'url'       =>  Url::toRoute(['/goods/add', 'category' => $this->category->ID, 'mode' => 'lot'])
                        ],
                        '<li class="divider"></li>',
                        [
                            'label'     =>  'Категорию',
                            'url'       =>  Url::toRoute(['add', 'category' => $this->category->ID])
                        ],
                    ],
                ],
                '<li class="divider"></li>',
                [
                    'label' =>  $this->category->enabled == "1" ? "Выключить" : "Включить",
                    'options'   =>  [
                        'class' =>  'shutdown',
                        'data-attribute-categoryID' =>  $this->category->ID
                    ],
                    'url'   =>  '#'
                ],
                [
                    'label' =>  $this->category->canBuy == "1" ? "Не продавать" : "Продавать",
                    'options'   =>  [
                        'class' =>  'canBuy',
                        'data-attribute-categoryID' =>  $this->category->ID
                    ],
                    'url'   =>  '#'
                ]

            ]
        ]).'
</div>';
    }
}