<div class="form-group">
    <div>Тип скидки: &nbsp;
        <div class="btn-group" data-toggle="buttons">
            <label class="btn btn-default active">
                <input type="radio" checked="checked" name="discountType" value="2"> процент
            </label>
            <label class="btn btn-default">
                <input type="radio" name="discountType" value="1">сумма
            </label>
        </div>
    </div>
</div>
<br>
<div class="form-group">
    <div>
        <div class="btn-group" data-toggle="buttons">
            <label class="btn btn-default active">
                <input type="radio" checked="checked" name="discountRewriteType" value="2">весь заказ
            </label>
            <label class="btn btn-default" onclick="getSelectedGoods()">
                <input type="radio" name="discountRewriteType" value="1">выбраные товары
            </label>
        </div>
    </div>
</div>
<input type="hidden" name="orderID" value="<?=$order->id?>">
<input type="hidden" name="selectedItems" id="discountSelectedItems" value="">