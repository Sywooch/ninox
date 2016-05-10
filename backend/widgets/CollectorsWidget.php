<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.09.15
 * Time: 15:38
 */

namespace backend\widgets;

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

        $css = <<<'CSS'
    .collectors{
        position: relative;
        width: 100%;
        font-size: 0.1px;
        font-family: "Open Sans",serif;
        height: 30px;
        line-height: 0;
        zoom:1;
        padding: 0;
        margin: 30px 0;

        display: -webkit-box; /* Android 4.3-, Safari без оптимизации */
        -webkit-box-pack: justify; /* Android 4.3-, Safari без оптимизации */
        display: -webkit-flex; /* оптимизация для Safari */
        -webkit-justify-content: space-between; /* оптимизация для Safari */
        display: flex;
        justify-content: space-between;
        text-align: justify; /* IE10-, Firefox 12-22 */
        text-align-last: justify; /* IE10-, Firefox 12-22 */
        /*text-justify: newspaper; /* IE7- */
        /*zoom: 1; /* IE7- */
    }

    .collectors li{
        display: inline-block;
        text-align: left;
        list-style: none;
        font-size: 13px;
        line-height: 30px !important;
        vertical-align: middle !important;
    }

    .collectors li a, .collectors li span.totalOrders{
        height: 30px;
        text-align: center;
        vertical-align: middle;
        line-height: 30px;
        font-size: 18px;
        font-family: "Open Sans",serif;
        font-weight: 600;
    }

    .collectors li a{
        /*display: inline-block;
        height: 30px;
        width: 30px;*/
        padding: 3px 10px;
        background: #ffc76c;
        color: #000;
        border-radius: 3px;
        text-decoration: none;
    }

    .collectors li a:hover{
        background: #FFBE4C;
    }

    .collectors li span{
        display: inline-block;
    }

    .collectors li span.name{
        margin-top: 3px;
    }

    .collectors li span.totalOrders{
        color: #b5b5b5;
    }

    .collectors li.bad a{
        background: #ff6f6c;
    }

    .collectors li.bad a:hover{
        cursor: not-allowed;
    }
CSS;

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


            $items[] = Html::tag('li', Html::tag('span', $item['name'].':', ['class' => 'name']).' '.($this->showUnfinished ? Html::tag('a', isset($item['completedOrders']) ? $item['completedOrders'] : 0, [
                    'href'  =>  $link
                ]) : '').' '.Html::tag('span', isset($item['totalOrders']) ? $item['totalOrders'] : 0, ['class' => 'totalOrders']), [
                'class' =>  (!isset($item['completedOrders']) || $item['completedOrders'] == 0 ? 'bad' : '')
            ]);
        }

        return Html::tag('ul', implode('', $items), [
            'class' =>  'collectors'
        ]).Html::tag('div', '', ['class' => 'clearfix']);
    }

}