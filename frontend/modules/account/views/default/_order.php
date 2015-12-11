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
            <div class="seller">
            </div>
            <div class="sold-items">
                <?=$this->render('_items', [
                    'item' =>  [
                        'image'                 =>  '',
                        'order-profile'         =>  'фиолетовый квадрат',
                        'one-price'                 =>  '100 Грн.',
                        'sum'                 =>  '5',
                        'sum-price'                 =>  '500 Грн.',
                    ]
                ])?>
                <?=$this->render('_items', [
                    'item' =>  [
                        'image'                 =>  'https://i1.rozetka.com.ua/goods/7222/copy_canon_pixma_mg3240+usb_cable_6223b007_506aea18b0f14_7222551.jpg',
                        'order-profile'         =>  'принтер',
                        'one-price'                 =>  '10000 Грн.',
                        'sum'                 =>  '5',
                        'sum-price'                 =>  '50000 Грн.',

                    ]
                ])?>
            </div>
            <div class="delivery">
                <div class="delivery-type">
                    Доставка (Курьер по вашему адресу)
                </div>
                <div class="delivery-coast">
                    50 грн
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
