<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.01.16
 * Time: 17:26
 */

namespace frontend\widgets;


use yii\base\Widget;
use yii\bootstrap\Html;

class MainMenuWidget extends Widget{

    private $defaultItemParams = [
        'slider'    =>  true
    ];
    public $items = [];
    public $options = [];
    private $defaultOptions = [
        'firstLevelUlClass' =>  'header-menu-items',
        'firstLevelDivClass'=>  'header-menu-item-content',
        'wtfLevelDivClass'  =>  'header-menu-item-content-text',
        'otherUlClass'      =>  'square',
        'firstLevelLiClass' =>  'header-menu-item',
        'otherLiClass'      =>  '',
        'headerImageClass'  =>  'header-menu-item-image',
        'menuClass'         =>  'header-menu',
        'menuClassDiv'      =>  'header-menu-content',
    ];

    public function init(){
        $this->options = array_merge($this->defaultOptions, $this->options);
    }

    public function run(){
        foreach($this->items as $item){
            $items[] = $this->renderItem(array_merge($this->defaultItemParams, $item));
        }

        return Html::tag('div', Html::tag('div', Html::tag('ul', implode('', $items), [
            'class' =>  $this->options['firstLevelUlClass']
        ]), [
            'class' =>  $this->options['menuClassDiv']
        ]), [
            'class' =>  $this->options['menuClass']
        ]);
    }

    public function renderItem($item, $subitem = false){
        $menu = $submenu = '';

        if(!$subitem && !empty($item['image'])){
            $menu .= Html::tag('div', Html::img($item['image']), [
                'class' =>  $this->options['headerImageClass']
            ]);
        }

        $menu .= Html::a($item['label'], '/'.$item['url']);

        if(!empty($item['items'])){
            foreach($item['items'] as $sub){
                $submenu .= $this->renderItem($sub, true);
            }

            if(!$subitem){
                $submenu = Html::tag('div', Html::tag('ul', $submenu, [
                    'class' =>  $this->options['otherUlClass']
                ]), [
                    'class' =>  $this->options['wtfLevelDivClass']
                ]);

                if($item['slider']){
                    $menu .= $this->renderSlider();
                }

                $menu .= Html::tag('div', $submenu, [
                    'class' =>  $this->options['firstLevelDivClass']
                ]);
            }
        }

        return Html::tag('li', $menu, [
            'class' =>  $subitem ? $this->options['otherLiClass'] : $this->options['firstLevelLiClass']
        ]);
    }

    public function renderSlider(){
        return '';
    }

}