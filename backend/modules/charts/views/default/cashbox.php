<?php
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

/**
 * @var \backend\modules\charts\models\CashboxMonthReport $report
 */

$this->title = 'Отчёт по кассе';

$this->params['breadcrumbs'][] = $this->title;

\bobroid\sweetalert\SweetalertAsset::register($this);

$js = <<<'JS'
    $("body")
    .on('click', 'button.tookMoney', function(){
        swal({
            title: "Забрать деньги",
            text: "Введите сумму которую вы забрали",
            html: true,
            type: "input",
            showCancelButton: false,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Забратая сумма",
            inputValue: 0,
            confirmButtonText: 'Сохранить'
        },
        function(inputValue){
            if (inputValue === false) return false;

            if (inputValue === "") {
                swal.showInputError("Поле нельзя оставлять пустым!");
                return false
            }else if(inputValue == 0){
                swal.showInputError("Смысл забирать из кассы 0 грн.?");
                return false
            }
        
            $.ajax({
                type: 'POST',
                url: '/charts/cashbox',
                data: {
                    action: 'tookMoney',
                    value: inputValue
                }
            });
    
            swal.close();
    
            $.pjax.reload({container: '#cashboxStatsGrid-pjax'});
        });
    })
    .on('click', 'button.addMoney', function(){
        swal({
            title: "Положить деньги",
            text: "Введите сумму которую вы хотите оставить",
            html: true,
            type: "input",
            showCancelButton: false,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Оставленная сумма",
            inputValue: 0,
            confirmButtonText: 'Сохранить'
        },
        function(inputValue){
            if (inputValue === false) return false;

            if (inputValue === "") {
                swal.showInputError("Поле нельзя оставлять пустым!");
                return false
            }else if(inputValue == 0){
                swal.showInputError("Смысл оставлять в кассе 0 грн.?");
                return false
            }
        
            $.ajax({
                type: 'POST',
                url: '/charts/cashbox',
                data: {
                    action: 'addMoney',
                    value: inputValue
                }
            });
    
            swal.close();
    
            $.pjax.reload({container: '#cashboxStatsGrid-pjax'});
        });
    }).on('click', 'button.showExtended', function(){
        $('[data-remodal-id=daySummaryModal]').remodal().open();
        
        var btn = $(this);
        
        $.ajax({
            type: 'POST',
            url: '/charts/cashbox',
            data: {
                action: 'getDetailed',
                value: btn.attr('data-date')
            },
            success: function(data){
                $("[data-remodal-id=daySummaryModal]").html(data);
            }
        });
        
        //$("[data-remodal-id=daySummaryModal]").html($(this).attr('data-date'));
    }).on('opening', '.remodal', function () {
      $(this).html('<span class="fa fa-spin fa-spinner"></span>')
    });
JS;

$this->registerJs($js);

$modal = new \bobroid\remodal\Remodal([
    'cancelButton'  =>  false,
    'confirmButton' =>  false,
    'addRandomToID' =>  false,
    'options'       =>  [
        'hashTracking'  =>  false
    ],
    'id'            =>  'daySummaryModal'
]);

echo Html::tag('h1', $this->title, ['class' => 'page-header col-xs-12', 'style' => 'margin-top: 0']),
Html::button("{$report->year} год", [
    'class' =>  'btn btn-default'
]),
'&nbsp;&nbsp;',
Html::button(\Yii::$app->formatter->asDate($report->year.'-'.$report->month.'-01', 'LLLL'), ['class' => 'btn btn-default']),
'&nbsp;&nbsp;',
Html::tag('br'),
Html::tag('br'),
GridView::widget([
    'dataProvider'  =>  new ArrayDataProvider([
        'models'    =>  $report->stats,
    ]),
    'summary'       =>  false,
    'pjax'          =>  true,
    'id'            =>  'cashboxStatsGrid',
    'columns'       =>  [
        [
            'label'         =>  'Дата',
            'attribute'     =>  'date',
            'format'        =>  'raw',
            'width'         =>  '100px',
            'value'         =>  function($model){
                return Html::button(\Yii::$app->formatter->asDate($model->date, 'dd MMMM'), ['class' => 'btn btn-link showExtended', 'data-date' => $model->date, 'style' => 'padding: 0']);
            }
        ],
        [
            'label'         =>  'Заказов',
            //'attribute'     =>  'ordersCount',
            'format'        =>  'raw',
            'width'         =>  '40px',
            'value'         =>  function($model){
                return count($model->shopOrders);
            }
        ],
        [
            'label'         =>  'Самовывоз',
            //'attribute'     =>  'ordersCount',
            'format'        =>  'raw',
            'width'         =>  '40px',
            'value'         =>  function($model){
                return count($model->selfDeliveredOrders);
            }
        ],
        [
            'label'         =>  'Сумма',
            'attribute'     =>  'sum',
            'width'         =>  '214px',
            'value'         =>  function($model){
                if(empty($model->sum)){
                    return '-';
                }

                return "{$model->sum} грн.";
            }
        ],
        [
            'label'         =>  'Траты',
            'attribute'     =>  'expenses',
            'width'         =>  '214px',
            'contentOptions'   =>  function($model){
                return [
                    'class' =>  !empty($model->expenses) ? 'danger' : ''
                ];
            },
            'value'         =>  function($model){
                if(empty($model->expenses)){
                    return '-';
                }

                return "{$model->expenses} грн.";
            }
        ],
        [
            'label'         =>  'Забрано с кассы',
            'attribute'     =>  'took',
            'width'         =>  '214px',
            'contentOptions'   =>  function($model){
                return [
                    'class' =>  !empty($model->took) ? 'success' : ''
                ];
            },
            'value'         =>  function($model){
                if(empty($model->took)){
                    return '-';
                }

                return "{$model->took} грн.";
            }
        ],
        [
            'label'         =>  'Добавлено',
            'attribute'     =>  'added',
            'width'         =>  '214px',
            'contentOptions'   =>  function($model){
                return [
                    'class' =>  !empty($model->added) ? 'info' : ''
                ];
            },
            'value'         =>  function($model){
                if(empty($model->added)){
                    return '-';
                }

                return "{$model->added} грн.";
            }
        ],
    ]
]);

echo $modal->renderModal();
?>
<div class="bigGrayFooter" style="position: fixed; left: 0; bottom: 0; height: 140px; width: 100%;">
    <div style="border-top: 1px solid #ddd; background: #fff; height: 40px; padding: 10px 0">
        <div class="container" style="height: 20px;">
            За период с - по -
        </div>
    </div>
    <div style="background: #ddd">
        <div class="container" style="height: 100px">
            <div class="row" style="margin-top: 15px;">
                <div class="row col-xs-7">
                    <div class="col-xs-4">
                        <span>Сумма заказов</span>
                        <h3>100 грн</h3>
                    </div>
                    <div class="col-xs-4">
                        <span>Траты</span>
                        <h3>100 грн</h3>
                    </div>
                    <div class="col-xs-4">
                        <span>Остаток в кассе</span>
                        <h3>100 грн</h3>
                    </div>
                </div>
                <div class="col-xs-4 col-xs-offset-1 text-right" style="margin-top: 20px; position: relative">
                    <button class="btn btn-default btn-success tookMoney">Забрать деньги</button>
                    &nbsp;
                    <button class="btn btn-default btn-info addMoney">Добавить денег</button>
                </div>
            </div>
        </div>
    </div>
</div>
