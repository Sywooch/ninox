<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 03.11.15
 * Time: 15:52
 */

namespace backend\modules\orders\widgets;


use kartik\form\ActiveForm;
use yii\base\Widget;
use yii\helpers\Html;

class OrdersSearchWidget extends Widget{

    public $items = [];
    public $searchModel = null;
    public $containerOptions = [];

    private $defaultItemOptions = [
        'labelOptions'  =>  [
            'class' =>  'header'
        ],
        'containerOptions'  =>  [
            'class' =>  'panel'
        ],
        'contentOptions'    =>  [
            'class' =>  'panelContent'
        ]
    ];

    private $defaultContainerOptions = [
        'id' =>  'accordion'
    ];

    public function init(){
        if($this->searchModel == null){
            throw new \ErrorException("Невозможно сделать виджет поиска без модели поиска!");
        }

        $this->containerOptions = array_merge($this->defaultContainerOptions, $this->containerOptions);
    }

    public function run(){
        $items = [];

        //$this->getView()->registerJsFile('js/orders_search_widget.js');

        $form = new ActiveForm();
        //$this->getView()->registerJs("OrdersSearch('form#".$form->getId()."')");
        $form->begin();

        foreach($this->items as $item){
            $items[] = $this->renderOne(array_merge($this->defaultItemOptions, $item), $form);
        }
        /*echo AccordionWidget::widget([
            'items'     =>  $items,
            'id'        =>  'searchAccordion',
        ]);*/
        echo Html::tag('div', implode('', $items), $this->containerOptions);
        $form->end();
    }

    public function renderOne($item, $form){
        return Html::tag('div', Html::tag('div', $item['label'], $item['labelOptions']).Html::tag('div', $form->field($this->searchModel, $item['attribute'], ['inputOptions' => ['placeholder' => $this->searchModel->getAttributeLabel($item['attribute'])]])->label(false), $item['contentOptions']), $item['containerOptions']);
    }

}