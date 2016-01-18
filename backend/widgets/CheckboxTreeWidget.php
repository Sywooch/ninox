<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 18.01.16
 * Time: 17:11
 */

namespace backend\widgets;


use backend\assets\CheckboxTreeAsset;
use yii\base\Widget;
use yii\helpers\Html;

class CheckboxTreeWidget extends Widget{

    public $options = [];
    private $defaultOptions = [];

    public $containerOptions = [];
    private $defaultContainerOptions = [];

    public $itemOptions = [];
    private $defaultItemOptions = [
        'value'     =>  1,
        'type'      =>  'checkbox',
        'options'   =>   []
    ];

    public $items;

    public function init(){
        $this->options = array_merge($this->defaultOptions, $this->options);
        $this->containerOptions = array_merge($this->defaultContainerOptions, $this->containerOptions);
        $this->itemOptions = array_merge($this->itemOptions, $this->defaultItemOptions);
    }

    public function run(){
        CheckboxTreeAsset::register($this->getView());

        $items = [];

        foreach($this->items as $item){
            $items[] =  $this->renderItem(array_merge($this->itemOptions, $item));
        }

        return Html::tag('div', implode('', $items), $this->containerOptions);
    }

    public function renderItem($item, $subitem = false){
        $content = '';

        $content .= Html::input($item['type'], $item['name'], $item['value'], array_merge(['id' => $item['name'].'_'.$item['value']], $item['options']));
        $content .= Html::label($item['label'], $item['name'].'_'.$item['value']);

        if(isset($item['items'])){
            $subitems = [];

            foreach($item['items'] as $sItem){
                $subitems[] = $this->renderItem(array_merge($this->itemOptions, $sItem), true);
            }

            $content .= Html::tag('ul', implode('', $subitems));
        }

        return Html::tag('li', $content);
    }

}