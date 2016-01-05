<?php
use yii\helpers\Html;
?>
<table class="printOrder">
    <tbody>
    <tr>
        <td class="print_head">
            <?=Html::tag('span', $order->canChangeItems != 1 ? 'Замену в заказе не делать!!! Штраф!!!' : 'Замену в заказе можно делать.', [
                'style' =>  'color: '.($order->canChangeItems != 1 ? 'red' : 'blue').'; font-size: 24px;'
            ])?>
            <div class='leader'>
                Содержимое заказа №<?=$order->id?>
                <span style="font-size:14px;">
                    подтвержден<?=Html::input('checkbox', '', '', [
                        ($order->confirmed == 1 ? 'checked' : 'unchecked') => 'checked'
                    ])?>
                </span>
            </div>
            <?='isNewCustomer?'//$order->client ? 'Клиент новый' : 'Клиент раньше делал заказ'?>
            <br>
            Дата поступления: <?=\Yii::$app->formatter->asDate($order->added, 'php:d.m.Y - H:i')?><br>
            Город: <?=$order->deliveryCity?><br>
            <span style="font-size:11px;color:#f00;">Комментарии: <?=$order->customerComment?></span>
        </td>
        <td style="font-size:26px; vertical-align:bottom;" class="print_head">ИТОГО:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><?=$order->summ?>грн.</td>
    </tr>
    <tr>
        <td colspan=2>
            <?php
            $count = count($orderItems);
            $first = true;
            foreach($orderItems as $item){
                $ico = strtolower($goods[$item->itemID]->ico);
                $i++;
                $n++;
                /*
                if($item->zamena == '1' || $order['nalichie'] == '1'){
                    if($order['zamena'] == '1' && $order['nalichie'] == '0'){
                        $img = 'zamena.png';
                    }elseif($order['zamena'] == '0' && $order['nalichie'] == '1'){
                        $img = 'zamena.png';
                    }else{
                        $img = 'zamena.png';
                    }
                }else{
                    $img = 'zamena.png';
                }
                TODO: этот код вывел меня из равновесия
                */
                ?>
                <?php if($i == '1'){ ?>
                    <table class='pageend'>
                <?php
                }
                ?>
                <?php if($i%2 == '1'){
                ?>
                <tr>
            <?php
            }
                ?>
                <td width="50%">
                    <li>
                        <div class="tovars2">
                            <div class="tovars_top2">
                                <table>
                                    <tr>
                                        <td class="tov_name" colspan="2">
                                            <span class="tov_title"><?=$n?>. <?=$good['Name']?>.</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <div class="tovars_img2" style="display: block;">
																<span class="fancybox" data-rel="tovar"  data-title="<?=$good['Name']?>" data-href="<?=$GLOBALS['CDN_LINK']?>/img/catalog/<?=$good['ico']?>">
																	<img alt="<?=$good['Name']?>. Купить бижутерию оптом — Krasota-Style" src="<?=$GLOBALS['CDN_LINK']?>/img/catalog/sm/<?=$good['ico']?>">
																</span>
                                                <div class="newTitle" style="display: none;">
                                                    <table>
                                                        <tr>
                                                            <td class="tovname"><?=$good['Name']?></td>
                                                            <td rowspan="2" class="cena"> <?=$good['PriceOut']?>.</td>
                                                            <td rowspan="2">
                                                                <span class="fancy incart"></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>цена</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="130">&nbsp;&nbsp;&nbsp;Код: <?=$good['Code']?><br/><br/>&nbsp;&nbsp;&nbsp;<?=$good['PriceOut']?> грн.&nbsp;<span ><?=$good['Qtty']?>шт.</span><br><br><img src="/template/img/<?=$img?>"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </li>
                </td>
                <?php if($i%2 == '0'){
                ?>
                </tr>
            <?php
            }
                ?>
                <?php
                if(($first && ($i == '6' || $i == $count)) || ($i == '8' || $i == $count)){
                    if($first){
                        $count = $count - 6;
                        $first = false;
                    }else{
                        $count = $count - 8;
                    }
                    $i = 0;
                    ?>
                    <tr>
                        <td class="telefon">Заказ №<?=$order->id?></td><td class="telefon">Менеджер: <?=\common\models\Siteuser::getUser($order->responsibleUser)?></td>
                    </tr>
                    </table>
                <?php
                }
            }
            ?>
        </td>
    </tr>
    </tbody>
</table>