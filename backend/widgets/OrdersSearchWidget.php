<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 03.11.15
 * Time: 15:52
 */

namespace backend\widgets;


use kartik\form\ActiveForm;
use yii\base\Widget;

class OrdersSearchWidget extends Widget{

    public $items = [];
    public $searchModel = null;

    public function init(){
        if($this->searchModel == null){
            throw new \ErrorException("Невозможно сделать виджет поиска без модели поиска!");
        }
    }

    public function run(){
        $items = [];

        $this->getView()->registerJsFile('js/orders_search_widget.js');

        $form = new ActiveForm();
        $this->getView()->registerJs("OrdersSearch('form#".$form->getId()."')");
        $form->begin();

        foreach($this->items as $item){
            $items[] = $this->renderOne($item, $form);
        }
        echo \bobroid\asaccordion\Widget::widget([
            'items' =>  $items,
            'id'    =>  'searchAccordion'
        ]);
        $form->end();
    }

    public function renderOne($item, $form){
        return [
            'header' =>  $item['label'],
            'content'   =>  $form->field($this->searchModel, $item['attribute'])
        ];
    }

}