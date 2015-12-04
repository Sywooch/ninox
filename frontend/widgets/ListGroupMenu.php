<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.12.15
 * Time: 16:52
 */

namespace frontend\widgets;


use yii\base\Widget;
use yii\helpers\Html;

class ListGroupMenu extends Widget{

    public $items;

    public function run(){
        $items = [];

        foreach($this->items as $item){
            $items[] = Html::a($item['label'], $item['href'], [
                'class' =>  'list-group-item'.(\Yii::$app->request->url == $item['href'] ? ' active' : '')
            ]);
        }

        return Html::tag('div', implode($items), [
            'class' =>  'list-group'
        ]);
    }

}