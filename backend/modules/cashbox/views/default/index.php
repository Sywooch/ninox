<div class="vrbody">
    <div class="wrappercontroller top">
        <div class="content">
            <div class="headerleftcontroll">
                <div class="tenpx"></div>
                <div class="headerbutton buttonbig">
                    <a onclick="checkRealSumm()" href="javascript:void(null)">Продажа (F9)</a>
                </div>
                <div class="headerbutton">
                    <a onclick="processBarCodeWriteOff()" href="javascript:void(null)">Списание <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAPCAYAAADQ4S5JAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAADgSURBVHjalNG9SgRBEATgb5cz0UAxNzPoSfwJFMQ3MBAD30HwBUQwEwNTUXwODUxMVRQ5DA+Mz9hETMdkV5b1llsLGqa6uqaLmSLnrImU0iK2K/owGo0+m3rZGj7EGLdVjVNKBxMNKaV9XOAIC5jHMa5SSnu/jpyznLOIeIuIy5o3+tcRMax5M9IKHv3FE1ZrUkTEDE6rKM/4aBmWsIlznBQRsYsb/bBTYlZ/zJUdwgteJwmDjuGt6jzEeufHtXpFVVM3bFQ3F1jrY9CO0V7//Y9X+hrgDmdYnjL8jvufAQAPYD+FCJYpAAAAAABJRU5ErkJggg=="></a>
                </div>
                <div class="headerbutton">
                    <a onclick="clearCart()" href="javascript:void(null)">Очистить заказ</a>
                </div>
                <div class="headerbutton">
                    <a onclick="RollBackBarCode()" href="javascript:void(null)">Возврат</a>
                </div>
                <div class="headerbutton">
                    <a onclick="processBarCodeDefect()" href="javascript:void(null)">Брак <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAPCAYAAADQ4S5JAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAADgSURBVHjalNG9SgRBEATgb5cz0UAxNzPoSfwJFMQ3MBAD30HwBUQwEwNTUXwODUxMVRQ5DA+Mz9hETMdkV5b1llsLGqa6uqaLmSLnrImU0iK2K/owGo0+m3rZGj7EGLdVjVNKBxMNKaV9XOAIC5jHMa5SSnu/jpyznLOIeIuIy5o3+tcRMax5M9IKHv3FE1ZrUkTEDE6rKM/4aBmWsIlznBQRsYsb/bBTYlZ/zJUdwgteJwmDjuGt6jzEeufHtXpFVVM3bFQ3F1jrY9CO0V7//Y9X+hrgDmdYnjL8jvufAQAPYD+FCJYpAAAAAABJRU5ErkJggg=="></a>
                </div>
                <div class="headerbutton">
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
            <tr class="tradd">
                <td width="20px"></td>
                <td class="tdborder" id="lastinsertindex" width="40px" align="center">1</td>
                <td class="tdborder" colspan="2">
                    <div>
                        <input id="BarCodeSearch" name="barcode" value="" type="text">
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
                <div class="headerbutton"><a href="/cashbox/getlistasidecarts">Чеки</a></div>
                <div class="headerbutton"><a href="/cashbox/archive">Архив</a></div>
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
    <div id="ClientWindow" class="modal" style="width: 500px;">
        <h2>Введите информацию о клиенте</h2>

        <div style="display: block;">
            <div id="bobroidsTabs">
                <div class="tabsLinks">
                    <div tabname="ext" class="oneTabItem active">Поиск</div>
                    <div subclass="blue" tabname="new" class="oneTabItem blue">Новый</div>
                    <!--<div subclass="blue" tabname="editUser" class="oneTabItem blue">Редактировать</div>-->
                </div>
                <div
                    style="width: 100%; padding-top: 10px; box-sizing: border-box; margin-top: -1px; border-top: 1px solid #ddd;"
                    class="tabInfo">
                    <div style="display: block;" id="ext">
                        <h3>Выбор существующего клиента:</h3>
                        <br>

                        <div>
                            <div style="display: block;" class="oneInput">
                                <input id="clientcardnumber" type="text"><label for="clientcardnumber">Номер
                                    карты</label>
                            </div>
                            или
                            <br>

                            <div style="display: block;" class="oneInput">
                                <input id="oldPhone" type="text"><label for="oldPhone">Телефон клиента</label>
                            </div>
                            или
                            <br>

                            <div style="display: block;" class="oneInput">
                                <input id="SearchSurname" type="text"><label for="SearchSurname">Фамилия</label>
                            </div>
                            <center>
                                <button onclick="findSomeUser(this)" class="coolButton green">Искать</button>
                            </center>
                        </div>
                    </div>
                    <div style="display: none;" id="new">
                        <h3>Добавление нового клиента:</h3>
                        <br>

                        <div>
                            <div style="display: block;" class="oneInput">
                                <input id="newSurname" type="text"><label for="newSurname">Фамилия</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input id="newName" type="text"><label for="newName">Имя</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input id="newCity" type="text"><label for="newCity">Город, Область</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input id="newPhone" type="text"><label for="newPhone">Номер телефона</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input id="newEmail" type="text"><label for="newEmail">Электронная почта</label>
                            </div>
                            <div style="display: block;" class="oneInput">
                                <input id="newClientCardNumber" type="text"><label for="newClientCardNumber">Номер
                                    карты</label>
                            </div>
                            <center>
                                <button onclick="addNewUser()" class="coolButton green">Добавить</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="ErrorQttyWindow" class="modal" style="width: 600px;"></div>
    <div id="ManagerWindow" class="modal" style="width: 420px;">
        <h2>Выберите менеджера</h2>

        <div class="headerbutton" data-id="11">Леся</div>
        <div class="headerbutton" data-id="14">Оля</div>
        <div class="headerbutton green" data-id="25">Михайло</div>
        <div class="headerbutton" data-id="31">пані Оля</div>
        <div class="headerbutton" data-id="33">Виктория</div>
        <div class="headerbutton" data-id="42">kasir</div>
        <div class="headerbutton" data-id="39">Анна</div>
        <div class="headerbutton" data-id="57">Женя</div>
    </div>
    <div id="CheckRealSumm" class="modal" style="width: 400px;">
        <h2>Введите сумму к оплате</h2>
        <table width="100%">
            <tbody>
            <tr>
                <td>
                    Текущая сумма:
                </td>
                <td>
                    <input disabled="" name="currentsumm" id="currentsumm" value="" type="text"> грн.
                </td>
            </tr>
            <tr>
                <td>
                    К оплате:
                </td>
                <td>
                    <input name="summkoplate" id="summkoplate" value="" type="text"> грн.
                </td>
            </tr>
            </tbody>
        </table>
        <h2 onclick="expandDiscountRule()">Добавить скидку</h2>

        <div id="newRule" style="display: none;">
            <br>
            <span>
                <img style="max-height: 30px;" alt="Добавить условие" title="Добавить условие" id="addTerm" src="//krasota-style.com.ua/template/images/add.svg">
                <label style="line-height: 30px; vertical-align: middle; height: 30px; display: inline-block; margin-top: -20px; margin-left: 10px;" for="addTerm">Добавить условие</label>
            </span>

            <div id="terms"></div>

            <div id="discount">
                <label for="discountPercent">Скидка:&nbsp;</label>
                <input id="discountPercent" style="width: 30px" max="100" maxlength="3" type="text">
                <label for="discountPercent">%</label>
            </div>
            <br>
            <br style="margin-bottom: 10px;">

            <div id="hiddenRule" style="display: none; min-height: 20px; padding: 5px; margin-bottom: 10px;"></div>
            <button id="addTermToBase">Добавить правило</button>
        </div>
        <button onclick="processBarCodeOrder()" class="coolButton button-small">Оформить заказ</button>
    </div>
</div>
<form id="clearCart" method="post"></form>
<form id="setAsideCart" method="post"></form>
<form id="processBarCodeOrder" method="post"></form>
<form id="addBarCodeOrder" method="post"></form>
<div id="breaksound"></div>