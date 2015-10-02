<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.09.15
 * Time: 15:38
 */

namespace common\components;

use yii\base\Widget;
use yii\helpers\Html;

class CollectorsWidget extends Widget{

    public $items = [];

    public $showUnfinished = true;

    public function init(){

    }

    public function run(){
        if(empty($this->items)){
            return;
        }

        $css = <<<'STYLE'
.collectors{
        display: table;
        position: relative;
        width: 100%;
        font-size: 13px;
        font-family: "Open Sans";
        height: 30px;
        margin: 15px 0;
    }

    .collectors li{
        display: table-cell;
        list-style: none;
    }

    .collectors li a, .collectors li span{
        height: 30px;
        text-align: center;
        vertical-align: middle;
        line-height: 30px;
        font-size: 18px;
        font-family: "Open Sans semibold";
    }

    .collectors li a{
        display: inline-block;
        height: 30px;
        width: 30px;
        background: #ffc76c;
        color: #000;
        border-radius: 3px;
        text-decoration: none;
    }

    .collectors li a:hover{
        background: #FFBE4C;
    }

    .collectors li span{
        color: #b5b5b5;
    }

    .collectors li.bad a{
        background: #ff6f6c;
    }

    .collectors li.bad a:hover{
        cursor: not-allowed;
    }
STYLE;

    $this->getView()->registerCss($css);

        $items = [];

        foreach($this->items as $item){
            if(!isset($item['userID']) || (!isset($item['completedOrders']) || $item['completedOrders'] == 0)){
                $link = '#';
            }else{
                if(!empty(\Yii::$app->request->get()) && !preg_match('/(|\?|&)responsibleUser/', \Yii::$app->request->url)){
                    $link = \Yii::$app->request->url.'&responsibleUser='.$item['userID'];
                }else{
                    $link = \Yii::$app->request->url;
                    if(preg_match('/(|\?|&)responsibleUser/', $link)){
                        $link = preg_replace('/(|\?|&)responsibleUser=\d+/', '', $link);
                    }
                    $link = $link.'?responsibleUser='.$item['userID'];
                }
            }


            $items[] = Html::tag('li', $item['name'].': '.($this->showUnfinished ? Html::tag('a', isset($item['completedOrders']) ? $item['completedOrders'] : 0, [
                    'href'  =>  $link
                ]) : '').' '.Html::tag('span', isset($item['totalOrders']) ? $item['totalOrders'] : 0), [
                'class' =>  (!isset($item['completedOrders']) || $item['completedOrders'] == 0 ? 'bad' : '')
            ]);
        }

        return Html::tag('ul', implode('', $items), [
            'class' =>  'collectors'
        ]).'<div style="clear: both"></div>';
    }

}