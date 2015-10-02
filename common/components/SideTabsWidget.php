<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 23.06.15
 * Time: 16:43
 */

namespace common\components;

use yii\base\Widget;
use yii\bootstrap\Modal;

class SideTabsWidget extends Widget{

    public $model;
    public $header = 'Изменения';
    public $dataProvider;
    public $changesButtonLabel = '<i class="glyphicon glyphicon-list-alt"></i> Список изменений';
    public $pageSize = '20';
    public $changesButtonClass = 'btn btn-default';
    public $items = [];
    public $options = [];

    public function init(){
        $this->items = array_merge(\Yii::$app->params['sideTabs'], $this->items);
    }


    public function run(){
        $css = <<<'STYLE'
            .screenPanel{
                position: fixed;
                vertical-align: middle;
                display: block;
                top: 50%;
                z-index: 1000;
                transform: translate(0%, -50%);
            }


            .screenPanel.screenPanel-left{
                left: -4px;
            }

            .screenPanel.screenPanel-right{
                right: -4px;
            }

            .screenPanel-right > *{
                text-align: left;
                padding-right: 20px !important;
            }

            .screenPanel-left > *{
                text-align: right;
                padding-left: 20px !important;
            }

            .screenPanel > *{
                padding: 5px;
                border-radius: 6px;
                font-size: 20px;
                min-height: 10px;
                display: block;
                margin-bottom: 10px;
                color: #333;
                background-color: #fff;
                border: 1px solid #ccc;
            }

            .innerPanel.size-small{
                font-size: 14px;
            }

            .innerPanel.type-alert{
                background-color: #F22;
                border: 1px solid #F22;
            }
STYLE;

        $this->getView()->registerCss($css);


        $items = [
            'left'     =>  [],
            'right'    =>  []
        ];

        $panelContent = '';

        foreach($this->items as $item){
            if($item['position'] == 'left'){
                $items['left'][] = $item;
            }else{
                $items['right'][] = $item;
            }
        }

        foreach($items as $position => $subItem){
            if(sizeof($subItem) >= 1){

                if(!isset($this->options['class'])){
                    $this->options['class'] = 'screenPanel screenPanel-'.$position;
                }else{
                    //TODO
                    $this->options['class'] = 'screenPanel screenPanel-'.$position;
                }

                $panelContent .= '<div';
                foreach($this->options as $k=>$v){
                    $panelContent .= ' '.$k.'="'.$v.'"';
                }
                $panelContent .= '>';

                foreach($subItem as $item){
                    if(isset($item['type'])){
                        switch($item['type']){
                            case 'modal':
                                $panelContent .= $this->createModal($item);
                                break;
                            default:
                                $panelContent .= $this->createContent($item);
                        }
                    }else{
                        $panelContent .= $this->createContent($item);
                    }
                }

                $panelContent .= '</div>';
            }
        }

        return $panelContent;
    }

    private function createModal($item){
        Modal::begin([

        ]);
    }

    private function createContent($item){
        $return = '';

        $return .= '<';

        if(isset($item['tag'])){
            $return .= $item['tag'];
        }else{
            $return .= 'div';
        }

        if(!isset($item['options'])){
            $item['options'] = [];
        }

        if(!isset($item['options']['class'])){
            $item['options']['class'] = '';
        }

        $item['options']['class'] = 'innerPanel '.$item['options']['class'];

        if(isset($item['options'])){
            foreach($item['options'] as $k=>$v){
                $return .= ' '.$k.'="'.$v.'"';
            }
        }

        $return .= '>';

        if(isset($item['label'])){
            $return .= $item['label'];
        }

        if(isset($item['type'])){
            switch($item['type']){
                case 'modal':
                    if(!isset($item['modalConfig'])){
                        $item['modalConfig'] = [
                            'content'   =>  ''
                        ];
                    }
                    $return .= $this->generateModal($item['modalConfig']);
                    break;
            }
        }

        $return .= '</';
        if(isset($item['tag'])){
            $return .= $item['tag'];
        }else{
            $return .= 'div';
        }
        $return .= '>';

        return $return;
    }
}