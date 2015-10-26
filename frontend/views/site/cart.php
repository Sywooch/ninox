<div class="block">
    <div class="caption">
        <div class="description">
            <span id="description">Корзина</span>
            <span id="description2">Ваша корзина пуста</span>
        </div>
        <div class="continue">
            <span class="closeCart" data-remodal-action="close">
                <span>Продолжить покупки</span>
                <span></span>
            </span>
        </div>
    </div>
    <div class="topShadow"></div>
    <div class="windowContent">
        <?=\yii\grid\GridView::widget([
            'dataProvider'  =>  $dataProvider,
            'emptyText'     =>  \Yii::t('shop', 'Ваша корзина пуста!')
        ])?>
    </div>
    <div class="miniFooter">
        <div class="bottomShadow"></div>
        <form method="POST" action="/order" onkeypress="if(event.keyCode == 13) return false;" onsubmit="submitForm(this); return false;">
            <div class="row first">
                <span id="optSumm" class="semi-bold">Сумма заказа по оптовым ценам: 0 грн</span>
                <span>Сумма заказа <span id="totalSumm" class="blue">0 грн</span></span>
            </div>
            <div class="row second">
                <span id="optSummDiff">Ваша корзина пуста</span>
				<span class="extended-info">
					<span id="totalSummWithoutDiscount">сумма заказа без скидки 0 грн</span> <span id="totalDiscount" class="orange">скидка 0 грн</span>
				</span>
            </div>
            <div class="row third">
                <div class="phone">
                    <span class="flag"></span><input placeholder="+_(___)___-____" class="input_phone" name="phone" value="" type="text">
                </div>
                <input id="one_click" class="yellowButton largeButton" value="Заказать в 1 клик" data-disabled="true" onclick="this.form.oneClickOrder = true" type="submit">
                <input id="checkout" class="yellowButton largeButton" value="Оформить заказ" data-disabled="true" onclick="this.form.oneClickOrder = false" type="submit">
                <input value="true" name="doOrder" type="hidden">
            </div>
        </form>
    </div>
</div>