<div class="order <?=$windowClass?>">
    <div class="waiting spoiler-title">
        <i class="icon icon-arrow"></i>
        <div class="myriad">
            <?=$order['number']?>
        </div>
        <div class="data semi">
            <?=$order['date']?>
        </div>
        <div class="payment semi">
            <?=$order['status']?>
        </div>
        <div class="money semi">
            <?=$order['summ']?> грн.
        </div>
    </div>
    <div class="pr">
        <div class="print semi">
            <i class="icon icon-print"></i>
        </div>
        <div class="history semi">
            <a>История</a>
        </div>
        <div class="reorder semi">
            <a>Повторить заказ</a>
        </div>
    </div>
    <div class="spoiler-body" style="display: none;">
        <div class="body">
            <hr>
            <div class="seller">
                Продавец : Krasota-style
            </div>
            <div class="sold-items">
                <?=$this->render('_items', [
                    'item' =>  [
                        'image'                 =>  'img/site/sven.jpg',
                        'order-profile'         =>  'фиолетовый квадрат',
                        'price'                 =>  '100$',
                        'one-price'                 =>  '100$',
                        'sum'                 =>  '5',
                        'sum-price'                 =>  '500',
                    ]
                ])?>
                <?=$this->render('_items', [
                    'item' =>  [
                        'image'                 =>  123,
                        'order-profile'         =>  'еще один квадрат',
                        'price'                 =>  '10200$',
                        'one-price'                 =>  '100$',
                        'sum'                 =>  '5',
                        'sum-price'                 =>  '500',

                    ]
                ])?>
            </div>
            <div class="delivery">
                <div class="delivery-type">
                    Доставка
                </div>
                <div class="delivery-coast">
                    50грн
                </div>
            </div>
            <div class="total-price">
                <div class="title">
                    Итого к оплате:
                </div>
                <div class="sum">
                    2554411 Грн.
                </div>
            </div>
        </div>
    </div>
</div>
