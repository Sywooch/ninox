<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 03.11.15
 * Time: 15:52
 */

namespace backend\widgets;


use yii\base\Widget;

class OrdersSearchWidget extends Widget{

    public $items = [
        [
            'header'    =>  '№ заказа',
            'content'   =>  'fasd',
        ],[
            'header'    =>  'Телефон',
            'content'   =>  'fasd',
        ],[
            'header'    =>  'Фамилия',
            'content'   =>  'fasd',
        ],[
            'header'    =>  'Эл. адрес',
            'content'   =>  'fasd',
        ],[
            'header'    =>  'ТТН',
            'content'   =>  'fasd',
        ],[
            'header'    =>  'Сумма',
            'content'   =>  'fasd',
        ],
    ];

    public $searchModel = null;

    public function init(){
        if($this->searchModel == null){
            throw new \ErrorException("Невозможно сделать виджет поиска без модели поиска!");
        }
    }

    public function run(){
        $items = [];

        foreach($this->items as $item){
            $items[] = $this->renderOne($item);
        }

        echo \bobroid\asaccordion\Widget::widget([
            'items' =>  $this->items
        ]);
    }

    public function renderOne($item){
        return $item;
    }

}