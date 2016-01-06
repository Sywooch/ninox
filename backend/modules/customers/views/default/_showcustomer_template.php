<?php
use \kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;

$this->title = "Клиент ".$customer->Company;
$this->params['breadcrumbs'][] = [
    'label' =>  'Клиенты',
    'url'   =>  '/customers'
];
$this->params['breadcrumbs'][] = $customer->Company;

$css = <<<'STYLE'
.circle{
    background: rgba(255, 255, 255, 0.99);
    border: 1px solid #E3E3E3;
    border-radius: 100%;
    height: 80px;
    width: 80px;
    overflow: hidden;
}

.circle-info{
    display: block;
    font-size: 32px;
    margin-top: 10px;
    text-align: center;
}

.circle-span{
    font-size: 12px;
    margin-top: -10px;
    text-align: center;
    display: block;
    opacity: 1;
}

.admin-orders-background::after{
  background-image: url('/img/purchase.png');
}

.admin-orderssumm-background::after{
  background-image: url('/img/ukraine2.png');
}

.admin-money-background::after{
  background-image: url('/img/coins24.png');
}

.admin-background::after {
  content: "";
  background-repeat: no-repeat;
  background-position: center;
  opacity: 0.07;
  top: 0;
  left: 16px;
  bottom: 0;
  right: 0;
  position: absolute;
  border-radius: 100%;
  border: none;
  height: 80px;
  width: 80px;
}

.customer-data-table tr td:first-child{
    text-align: right;
    font-weight: bold;
    width: 40%;
}

.customer-data-orders thead th{
    text-align: center;
}

STYLE;

$form = '';
$lables = [];
if($editMode){
    $form = ActiveForm::begin([
        'id' => 'login-form-horizontal',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]);
}else{
    if($customer->blackList == 1){
        $lables[] = '<span class="label label-danger">в чёрном списке</span>';
    }

    if($customer->deleted != 0){
        $lables[] = '<span class="label label-info">удалён</span>';
    }
}


$this->registerCss($css);
?>
<h1><?=$customer->Company?>&nbsp;<small>Клиент</small></h1>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="btn-group pull-left" role="group">
            <?=\backend\widgets\ChangesWidget::widget([
                'model'         =>  $customer,
                'header'        =>  'Изменения по клиенту '.$customer->Company
            ])?>
        </div>
        <?=$editMode ? '<button class="btn btn-success pull-right" type="submit" style="margin-left: 10px;">Сохранить</button>' : ''?>
        <div class="btn-group pull-right" role="group" aria-label="...">
            <button type="button" id="changeTrashState" class="btn btn-info" data-customer-id="<?=$customer->ID?>"><?=$customer->deleted == 0 ? 'Удалить' : 'Восстановить'?></button>
            <button type="button" id="changeState" class="btn btn-info" data-customer-id="<?=$customer->ID?>"><?=$customer->blackList == 0 ? 'В чёрный список' : 'Из чёрного списка'?></button>
            <a type="button" class="btn btn-info" href="/customers/showcustomer/<?=$customer->ID.(!$editMode ? '?act=edit' : '')?>"><?=$editMode ? 'В режим просмотра' : 'Редактировать'?></a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <?=$this->render($editMode ? 'editcustomer' : 'showcustomer', [
            'customer'      =>  $customer,
            'ordersStats'   =>  $ordersStats,
            'orders'        =>  $orders,
            'lastOrder'     =>  $lastOrder,
            'form'          =>  $form,
            'lables'        =>  $lables
        ])?>
    </div>
</div>