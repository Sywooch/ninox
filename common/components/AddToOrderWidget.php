<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.06.15
 * Time: 16:23
 */

namespace common\components;

use app\assets\SweetalertAsset;
use app\models\History;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;

class AddToOrderWidget extends Widget{

    public $buttonClass =   'btn btn-default';
    public $buttonText  =   'Добавить в заказ';
    public $modalHeader =   'Добавить товар в заказ';
    public $pageSize    =   '20';
    public $query       =   null;
    public $itemID;
    public $dataProvider;

    public function init(){
        if($this->query == null){
            $this->query = History::find();
        }

        $this->dataProvider = new ActiveDataProvider([
            'query' =>  $this->query->orderBy('id DESC'),
            'pagination'    =>  [
                'pageSize'  =>  $this->pageSize
            ]
        ]);
    }

    public function run(){
        $css = <<<'STYLE'
.orderRow{
    cursor: pointer;
}
STYLE;

        $js = <<<'SCRIPT'
    var addItemToOrder = function(e){
        var item = e.target.parentNode;
        bootbox.prompt("Сколько добавить товара в заказ?", function(result){
            if(result !== null){
                $.ajax({
                    type: 'POST',
                    url: '/admin/goods/additemtoorder',
                    data: {
                        'OrderID': item.getAttribute("data-key"),
                        'itemID':  item.getAttribute("data-attribute-itemID"),
                        'ItemsCount': result
                    },
                    success: function(data){

                    }
                });
            }
        });
    }, addEventToTableRow = function(){
        var a = document.querySelectorAll(".orderRow");
        for(var i = 0; i < a.length; i++){
            a[i].addEventListener('click', addItemToOrder, false);
        };
    };

    addEventToTableRow();
SCRIPT;

        $this->getView()->registerJs($js);
        $this->getView()->registerCss($css);
        $this->getView()->registerJsFile('/js/bootbox.min.js', [
            'depends'   =>  [
                'yii\web\JqueryAsset'
            ]
        ]);

        Modal::begin([
            'header' => $this->modalHeader,
            'options'   =>  [
                'style' =>  'color: black'
            ],
            'toggleButton' => [
                'label'     =>  $this->buttonText,
                'class'     =>  $this->buttonClass
            ],
            'size'  =>  Modal::SIZE_LARGE,
        ]);
        Pjax::begin();
        echo \kartik\grid\GridView::widget([
            'dataProvider'  =>  $this->dataProvider,
            'summary'  =>  '',
            'hover'     =>  true,
            'striped'   =>  true,
            'rowOptions'    =>  [
                'class' =>  'orderRow',
                'data-attribute-itemID'    =>  $this->itemID
            ],
            'bordered'  =>  false,
            'columns'   =>  [
                [
                    'attribute' =>  'id'
                ],
                [
                    'attribute' =>  'name',
                    'value'     =>  function($model){
                        return $model->customerSurname.' '.$model->customerName.' '.$model->customerFathername;
                    }
                ],
                [
                    'attribute' =>  'customerPhone'
                ],
                [
                    'attribute' =>  'customerEmail'
                ],
                [
                    'attribute' =>  'deliveryCity',
                    'value'     =>  function($model){
                        $r = '';
                        $r .= $model->deliveryCity;
                        $r .= !empty($model->deliveryRegion) ? ', '.$model->deliveryRegion : '';

                        return $r;
                    }
                ],
            ]
        ]);
        Pjax::end();
        Modal::end();
    }

}