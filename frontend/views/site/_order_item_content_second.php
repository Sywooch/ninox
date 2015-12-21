<div class="content-data-body-delivery-type">
    <?=$form->field($model, 'deliveryType')->radioList(\common\models\DeliveryTypes::getDeliveryTypes())->label(false)?>
    <?=\yii\bootstrap\Tabs::widget([
        'headerOptions' =>  [
            'style' =>  'display: '
        ],
        'items' =>  [
            [
                'content'   =>  '<div class="content-data-body-department">Отделение:</div>',
                'label'     =>  'Новая почта',
                'id'        =>  'newPost'
            ],
            [
                'content'   =>  '<div class="content-data-body-address">Мои адреса:</div>',
                'label'     =>  'Адресная доставка',
                'id'        =>  'delivery'
            ],
            [
                'content'   =>  '<div>
                                <div class="content-data-body-stock">


                                    <div class="semi-bold">Наш склад находится по адресу:</div>
                                    г. Киев, ул. Электротехническая, 2
                                    <div class="work-time">
                                        Время работы с 9:00 до 17:00
                                    </div>
                                    <div class="work-time">
                                    все дни кроме понедельника
                                    </div>
                                </div>
                                </div>',
                'label'     =>  'Самовывоз',
                'id'        =>  'post2'
            ],
        ]
    ])?>

</div>
<?=$form->field($model, 'anotherReceiver')->radioList([
    '0' =>  'Отправлять на меня',
    '1' =>  'Будет получать другой человек'
])->label(false)?>


<?=$form->field($model, 'payment')->radioList([
    '0' =>  'Наличными при получении (25 от сумы + 20 грн.)',
    '1' =>  'Оплата на карту ПриватБанк (1% от сумы)',
    '2' =>  'Visa / MasterCard (1% от сумы)'
])->label(false);
/*
echo $form->field($model, 'list')->widget(
    'stylekrasota\yii2\widgets\bootstrap\RadioList',
    [
        'items' =>
            [
                'value-1' => 'label-1',
                'value-2' => 'label-2',
                'value-3' => 'label-3'
            ],
        'type' => 'primary',
        'size' => 'default'
    ]
);

?>
<a>Добавить коментарий к заказу</a>