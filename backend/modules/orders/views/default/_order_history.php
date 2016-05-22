<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 22.05.16
 * Time: 15:53
 */
use yii\helpers\Html;

?>
<div class="col-xs-12 col-sm-6 col-md-8 block-span" style="text-align: left">
    <b>История заказа</b>
    <div class="blue-line"></div>
    <span><b>sdfgd</b>dfgdfg</span>
    <span><b>sdfgd</b>dfgdfg</span>
    <span><b>sdfgd</b>dfgdfg</span>
    <span><b>sdfgd</b>dfgdfg</span>
    <span><b>sdfgd</b>dfgdfg</span>
    <span><b>sdfgd</b>dfgdfg</span>
    <span><b>sdfgd</b>dfgdfg</span>
    <div class="blue-line"></div>
    <span style="float: left"><b>sdfgd</b>dfgdfg</span>
    <?php
    echo Html::tag('span', 'SMS', [
            'class' =>  'label label-info',
            'style' =>  'display: inline-block; border-radius: 10px; line-height: normal;'
        ])
    ?>


</div>
<div class="col-xs-6 col-md-4" style="text-align: left">
    <b>Статус смс</b>
    <div class="blue-line"></div>
    <ul>
        <li><span>Не дозвонились</span></li>
        <li><span>Не дозвонились</span></li>
        <li><span>Не дозвонились</span></li>
        <li><span>Не дозвонились</span></li>
    </ul>

</div>
