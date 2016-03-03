<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 03.03.16
 * Time: 13:18
 */

namespace backend\widgets;


use rmrevin\yii\fontawesome\FA;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class SmartFiltersWidget extends Widget
{

    public $items = [];

    public $filterKey = 'smartFilter';

    public $containerOptions = [];
    public $dropDownOptions = [];

    public $dropDownCaretFA = 'sort-desc';

    public $defaultItem = [
        'filter'        =>  false,
        'labelClass'    =>  '',
        'class'         =>  ''
    ];

    public function init(){
        $this->containerOptions = array_merge(['class' => ''], $this->containerOptions);
        $this->dropDownOptions = array_merge(['class' => 'dropdown-menu'], $this->dropDownOptions);
    }

    public function run(){
        $items = [];

        foreach($this->items as $item){
            $items[] = $this->renderItem($item);
        }

        $this->containerOptions['class'] .= ' nav nav-pills';

        return Html::tag('ul', implode('', $items), array_merge($this->containerOptions));
    }

    public function renderItem($item, $sub = false){
        $item = array_merge($this->defaultItem, $item);

        if(isset($item['counterValue']) && !$sub){
            $item['label'] .= Html::tag('span', $item['counterValue'], ['class' => 'label '.$item['labelClass']]);
        }

        if(empty($item['items']) && \Yii::$app->request->get($this->filterKey) == $item['filter']){
            $item['class'] .= ' active';
        }

        $routeOptions = [
            '/'.\Yii::$app->request->getPathInfo()
        ];

        if($item['filter']){
            $routeOptions[$this->filterKey] = $item['filter'];
        }

        if(!empty(\Yii::$app->request->get('category'))){
            $routeOptions['category'] = \Yii::$app->request->get('category');
        }

        $linkOptions = [];

        if(!empty($item['items'])){
            $item['label'] = $item['label'].'&nbsp;'.FA::i($this->dropDownCaretFA);

            $linkOptions['class']       = 'dropdown-toggle';
            $linkOptions['data-toggle'] = 'dropdown';
        }

        $url = Url::toRoute($routeOptions);

        $content = Html::a($item['label'], $url, $linkOptions);

        if(!empty($item['items']) && !$sub){
            $subItems = [];

            foreach($item['items'] as $subItem){
                $subItems[] = $this->renderItem($subItem, true);
            }

            $content .= Html::tag('ul', implode('', $subItems), $this->dropDownOptions);
        }

        $contentOptions = [
            'class' =>  $item['class']
        ];

        if(!$sub){
            $contentOptions['role'] = 'presentation';
        }

        return Html::tag('li', $content, $contentOptions);
    }

}