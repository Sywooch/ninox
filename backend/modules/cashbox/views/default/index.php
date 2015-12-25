<?php

use yii\bootstrap\Html;
$this->title = 'Касса';

$js = <<<'SCRIPT'
    var addItem = function(item){
        $.ajax({
            type: 'POST',
            url: '/cashbox/additem',
            data: {
                'itemID': item
            },
            success: function(data){

            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }

    $("#itemInput").on('keypress', function(e){
        e.currentTarget.value = e.currentTarget.value.replace(/\D+/, '');

        if(e.keyCode == 13 && e.currentTarget.value != ''){
            addItem(e.currentTarget.value);
        }
    });

    $("#itemInput").on('keyup', function(e){
        e.currentTarget.value = e.currentTarget.value.replace(/\D+/, '');
    });
SCRIPT;

$this->registerJs($js);


?>

<div class="no-padding header">
    <div class="content row">
        <div class="buttonsContainer col-xs-8">
            <div class="col-xs-2" style="padding: 0; margin-right: 12px;">
                <button class="btn btn-default btn-big" id="sellButton">Продажа (F9)</button>
            </div>
            <div class="manyButtons col-xs-10 row" style="margin-left: 0; padding: 0">
                <div class="col-xs-8" style="margin-left: 0; padding: 0">
                    <div class="buttonsRow row" style="margin-left: 0; padding: 0">
                        <a class="btn btn-default col-xs-4" href="#writeOffModal">Списание </a>
                        <button class="btn btn-default col-xs-4" id="clearOrder">Очистить заказ</button>
                        <a class="btn btn-default col-xs-4" href="#returnModal">Возврат</a>
                    </div>
                    <div class="buttonsRow row" style="margin-left: 0; padding: 0">
                        <a class="btn btn-default col-xs-4" href="#defectModal">Брак </a>
                        <a class="btn btn-default col-xs-4" href="#changeManagerModal">Михайло</a>
                    </div>
                </div>
                <div class="col-xs-3 col-xs-offset-1">
                    <a class="btn btn-default btn-sm" style="margin-bottom: 5px;">Отложить чек</a>
                    <a class="btn btn-default btn-sm" href="#customerModal">+ клиент</a>
                </div>
            </div>
        </div>
        <div class="col-xs-4 summary bg-danger">
            <p style="font-size: 14px;">Сумма: <span class="summ">0.00</span> грн. Скидка: <span class="discountPercent">0</span>% (<span class="discountSize">0</span> грн.)</p>
            <h2>К оплате: <span class="toPay">0.00</span> грн.</h2>

            <p>Количество товаров: <span class="itemsCount">0</span></p>
        </div>
    </div>
</div>
<div class="content main">
    <?=\kartik\grid\GridView::widget([
        'dataProvider'  =>  $orderItems,
        'id'            =>  'cashboxGrid',
        'summary'       =>  false,
        'emptyText'     =>  false,
    ])?>
    <div style="margin-top: -20px;">
        <input type="text" id="itemInput">
    </div>
</div>
<div class="footer">
    <div class="content">
        <div class="left">
            <a class="btn btn-default btn-lg" href="/cashbox/checks">Чеки</a>
            <a class="btn btn-default btn-lg" href="/cashbox/sales">Продажи</a>
        </div>
        <div class="right">
            <?=Html::button((\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 1 ? 'Опт' : 'Розница'), [
                'class' =>  'btn btn-lg btn-'.(\Yii::$app->request->cookies->getValue("cashboxPriceType", 0) == 0 ? 'danger' : 'success'),
                'id'    =>  'changeCashboxType',
            ])?>
        </div>
    </div>
</div>

<!--<div class="vrbody">
    <div class="wrappercontroller top">
        <div class="content">
            <div class="headerleftcontroll">
                <div class="tenpx"></div>
                <button class="headerbutton buttonbig" id="saleF9">Продажа (F9)</button>
                <button class="headerbutton" id="spisanie">Списание <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAPCAYAAADQ4S5JAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAADgSURBVHjalNG9SgRBEATgb5cz0UAxNzPoSfwJFMQ3MBAD30HwBUQwEwNTUXwODUxMVRQ5DA+Mz9hETMdkV5b1llsLGqa6uqaLmSLnrImU0iK2K/owGo0+m3rZGj7EGLdVjVNKBxMNKaV9XOAIC5jHMa5SSnu/jpyznLOIeIuIy5o3+tcRMax5M9IKHv3FE1ZrUkTEDE6rKM/4aBmWsIlznBQRsYsb/bBTYlZ/zJUdwgteJwmDjuGt6jzEeufHtXpFVVM3bFQ3F1jrY9CO0V7//Y9X+hrgDmdYnjL8jvufAQAPYD+FCJYpAAAAAABJRU5ErkJggg=="></button>
                <button class="headerbutton" id="dropOrder">Очистить заказ</button>
                <button class="headerbutton" id="rollback">Возврат</button>
                <button class="headerbutton" id="defect">Брак <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAPCAYAAADQ4S5JAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAADgSURBVHjalNG9SgRBEATgb5cz0UAxNzPoSfwJFMQ3MBAD30HwBUQwEwNTUXwODUxMVRQ5DA+Mz9hETMdkV5b1llsLGqa6uqaLmSLnrImU0iK2K/owGo0+m3rZGj7EGLdVjVNKBxMNKaV9XOAIC5jHMa5SSnu/jpyznLOIeIuIy5o3+tcRMax5M9IKHv3FE1ZrUkTEDE6rKM/4aBmWsIlznBQRsYsb/bBTYlZ/zJUdwgteJwmDjuGt6jzEeufHtXpFVVM3bFQ3F1jrY9CO0V7//Y9X+hrgDmdYnjL8jvufAQAPYD+FCJYpAAAAAABJRU5ErkJggg=="></button>


                <!--<div class="headerbutton buttonbig">
                    <a onclick="checkRealSumm()" href="javascript:void(null)">Продажа (F9)</a>
                </div>
                <div class="headerbutton">
                    <a onclick="processBarCodeWriteOff()" href="javascript:void(null)">Списание</a>
                </div>
                <div class="headerbutton">
                    <a onclick="clearCart()" href="javascript:void(null)">Очистить заказ</a>
                </div>
                <div class="headerbutton">
                    <a onclick="RollBackBarCode()" href="javascript:void(null)">Возврат</a>
                </div>
                <div class="headerbutton">
                    <a onclick="processBarCodeDefect()" href="javascript:void(null)">Брак <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAPCAYAAADQ4S5JAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAADgSURBVHjalNG9SgRBEATgb5cz0UAxNzPoSfwJFMQ3MBAD30HwBUQwEwNTUXwODUxMVRQ5DA+Mz9hETMdkV5b1llsLGqa6uqaLmSLnrImU0iK2K/owGo0+m3rZGj7EGLdVjVNKBxMNKaV9XOAIC5jHMa5SSnu/jpyznLOIeIuIy5o3+tcRMax5M9IKHv3FE1ZrUkTEDE6rKM/4aBmWsIlznBQRsYsb/bBTYlZ/zJUdwgteJwmDjuGt6jzEeufHtXpFVVM3bFQ3F1jrY9CO0V7//Y9X+hrgDmdYnjL8jvufAQAPYD+FCJYpAAAAAABJRU5ErkJggg=="></a>
                </div>-->
                <!--<div class="headerbutton">
                    <a id="managertext" onclick="openManagerWindow()" href="javascript:void(null)">Михайло</a>
                </div>
            </div>
            <div class="headerrightcontroll">
                <div class="controlblock">
                    <div class="tenpx"></div>
                    <div class="headerbutton">
                        <a onclick="setAsideCart()" href="javascript:void(null)">Отложить чек</a>
                    </div>
                    <div class="headerbutton">
                        <a class="clientlink" onclick="openClientWindow()" href="javascript:void(null)">+ клиент </a>
                    </div>
                </div>
                <div class="cartpreviewsumm red">
                    <div class="cartupdate">
                        <div class="previewsumm">
                            Сумма: <span id="BarCodeCartSummWithoutDiscount">0.00</span> грн.
                            Скидка: <span>0</span>% (<span id="DiscountSumm">0</span> грн.)
                        </div>
                        <div class="fullsumm">К оплате: <span id="BarCodeCartSumm">0.00</span> грн.</div>
                        <div>Количество товаров:</div>
                    </div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
    <div class="content information">
        <table id="listProductsBarCode" width="100%" cellspacing="0">
            <tbody>
                <tr id="2003728">
                    <td width="20px">
                        <a onclick="removegood(2003728)" href="javascript:void(null)">
                            <img src="/img/delete-icon.png">
                        </a>
                    </td>
                    <td class="tdborder" width="40px" align="center">1</td>
                    <td class="tdborder" width="40px" align="center">2003728</td>
                    <td class="tdborder">
                        <div>Серьги женские *Восход*, 1 шт.</div>
                    </td>
                    <td class="tdborder" width="60px" align="center">
                        <div>
                            <input class="changeqtty" data-id="2003728" value="0">
                        </div>
                    </td>
                    <td class="tdborder" width="90px" align="center"><span>0</span> грн.</td>
                    <td class="tdborder" width="130px" align="center"><span>0</span> грн.</td>
                    <td class="tdborder">-0%</td>
                </tr>
                <tr class="tradd">
                    <td width="20px"></td>
                    <td class="tdborder" id="lastinsertindex" width="40px" align="center">1</td>
                    <td class="tdborder" colspan="2">
                        <div>
                            <input id="BarCodeSearch" name="barcode" value="" type="text" placeholder="">
                        </div>
                    </td>
                    <td class="tdborder" width="60px" align="center"></td>
                    <td class="tdborder" width="90px" align="center"></td>
                    <td class="tdborder" width="130px" align="center"></td>
                    <td class="tdborder"></td>
                </tr>
            </tbody>
        </table>
        <div class="page-buffer"></div>
    </div>
</div>
<div id="vrfooter">
    <div class="wrappercontroller">
        <div class="content">
            <div class="headerleftcontroll">
                <div class="tenpx"></div>
                <div class="headerbutton"><a href="/cashbox/checks">Чеки</a></div>
                <div class="headerbutton"><a href="/cashbox/sales">Продажи</a></div>
            </div>
            <div class="headerrightcontroll">
                <div class="tenpx"></div>
                <div class="controlblock">
                    <div class="headerbutton red">
                        <a onclick="changeOptRozniza()" href="javascript:void(null)">Розница</a></div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>
<div style="display: none">


    <!--<div id="ErrorQttyWindow" class="modal" style="width: 600px;"></div>-->


    <!--<div id="ManagerWindow" class="modal" style="width: 420px;">
        <h2>Выберите менеджера</h2>
        <div class="headerbutton" data-id="11">Леся</div>
        <div class="headerbutton" data-id="14">Оля</div>
        <div class="headerbutton green" data-id="25">Михайло</div>
        <div class="headerbutton" data-id="31">пані Оля</div>
        <div class="headerbutton" data-id="33">Виктория</div>
        <div class="headerbutton" data-id="42">kasir</div>
        <div class="headerbutton" data-id="39">Анна</div>
        <div class="headerbutton" data-id="57">Женя</div>
    </div>-->

<!--</div>
<?php
$customerInfoModal = new \bobroid\remodal\Remodal();
?>
<form id="clearCart" method="post"></form>
<form id="setAsideCart" method="post"></form>
<form id="processBarCodeOrder" method="post"></form>
<form id="addBarCodeOrder" method="post"></form>
<div id="breaksound"></div>-->