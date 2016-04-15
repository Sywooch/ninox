<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.01.16
 * Time: 17:26
 */

namespace frontend\widgets;


use frontend\assets\MainMenuAsset;
use yii\base\Widget;
use yii\bootstrap\Html;

class MainMenuWidget extends Widget{

    private $defaultItemParams = [
        'slider'    =>  [
            [
                'img'   =>  '#',
            ],[
                'img'   =>  '#',
            ],[
                'img'   =>  '#',
            ],
        ]
    ];
    
    public $items = [];
    
    public $options = [];
    
    private $defaultOptions = [
        'firstLevelUlClass' =>  'header-menu-items',
        'firstLevelDivClass'=>  'header-menu-item-content',
        'wtfLevelDivClass'  =>  'header-menu-item-content-text',
        'otherUlClass'      =>  'header-menu-item-content-items',
        'firstLevelLiClass' =>  'header-menu-item',
        'otherLiClass'      =>  '',
        'headerImageClass'  =>  'header-menu-item-image',
        'menuClass'         =>  'header-menu',
        'sliderClassDiv'    =>  'header-menu-item-content-slider',
        'sliderClassImage'  =>  'img',
        'sticky'            =>  true
    ];

    public function init(){
        $this->options = array_merge($this->defaultOptions, $this->options);
    }

    public function run(){
        foreach($this->items as $item){
            $items[] = $this->renderItem(array_merge($this->defaultItemParams, $item));
        }

        MainMenuAsset::register($this->getView());

        if($this->options['sticky']){
            $this->getView()->registerJsFile('/js/jquery.sticky.js', [
                'depends'   =>  'yii\web\JqueryAsset'
            ]);

            $this->getView()->registerJs('$(".sticky-on-scroll").sticky({topSpacing: 0, className: "sticky"})');
        }

        return Html::tag('div', Html::tag('ul', implode('', $items), [
            'class' =>  $this->options['firstLevelUlClass']
        ]), [
            'class' =>  $this->options['menuClass']
        ]);
    }

    public function renderItem($item, $subitem = false){
        $menu = $submenu = '';

        if(!$subitem && !empty($item['imgSrc'])){
            $menu .= Html::tag('div', Html::img($item['imgSrc'], ['class' => 'svg']), [
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
                    $submenu .= $this->renderSlider($item['slider']);
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

    public function renderSlider($slider){
        $slides = [];

        foreach($slider as $slide){
            $slides[] = $this->renderSliderItem($slide);
        }

        return Html::tag('div', implode('', $slides), [
            'class' =>  $this->options['sliderClassDiv']
        ]);
    }

    public function renderSliderItem($item){
        return Html::img($item['img']).Html::tag('div', '', [
            'class' =>  $this->options['sliderClassImage']
        ]);
    }

}