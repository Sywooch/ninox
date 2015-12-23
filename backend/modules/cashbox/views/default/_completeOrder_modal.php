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