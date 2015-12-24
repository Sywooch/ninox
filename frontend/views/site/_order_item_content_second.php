<?php
use yii\helpers\Html;

$js = <<<'SCRIPT'
$('label.tabsLabels').click(function () {
    $(this).tab('show');
});
SCRIPT;

$this->registerJs($js);
?>
<div class="content-data-body-delivery-type">
    <?=$form->field($model, 'deliveryType', [
        //'inputTemplate' =>  '<div class="radio asd">{beginLabel}{input}{labelTitle}{endLabel}{error}{hint}</div>',
    ])->radioList(\common\models\DeliveryTypes::getDeliveryTypes(), [
        'item' => function ($index, $label, $name, $checked, $value) {
            return '<div class="tab">'. Html::radio($name, $checked, [
                'value'     =>      $value,
                'id'        =>      "tab-".$value
            ])
            .'<label class="tabsLabels" data-target="#w1-tab'.$value.'" for="tab-'.$value.'">'. $label .'</label>'.'</div>';
        },

          'itemOptions'   =>  [
            'class' =>  'asdf'
        ]
    ])->label(false)?>
    <?=\yii\bootstrap\Tabs::widget([
        'headerOptions' =>  [
            'style' =>  'display: none'
        ],
        'items' =>  [
            [
                'content'   =>  '',
                'label'     =>  '',
                'id'        =>  ''
            ],
            [
                'content'   =>  '<div class="content-data-body-address">Мои адреса:</div>',
                'label'     =>  'Адресная доставка',
                'id'        =>  '',
                'active' => true
            ],
            [
                'content'   =>  '<div class="content-data-body-department">Отделение:</div>',
                'label'     =>  'Новая Почта',
                'id'        =>  ''
            ],
            [
                'content'   =>  '',
                'label'     =>  '',
                'id'        =>  ''
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
                'id'        =>  ''
            ],
        ]
    ])?>
</div>

<div class="content-data-body-delivery-type">

<?php
for($i=1;$i<3;$i++){
    echo $i;
}?>
<?=$form->field($model, 'anotherReceiver')->radioList([
    '1' =>  'Отправлять на меня',
    '2' =>  'Будет получать другой человек',
    ],
    [
        'item'  =>  function ($index, $label, $name, $checked) {


            for ($i = 1; $i < 3; $i++) {


                echo Html::radio($name, $checked, [
                        'value' => $i,
                        'id' => $i
                    ])
                    . '<label class="tabsLabels" data-target="#w2-tab1" for="' . $i . '">' . $label . '</label>';

        }
    ]
)->label(false)?>
<?=\yii\bootstrap\Tabs::widget([
    'headerOptions' =>  [
        'style' =>  'display: none'
    ],
    'items' =>  [
        [
            'content'   =>  '',
            'label'     =>  '',
            'id'        =>  '1',
            'active' => true
        ],
        [
            'content'   =>  $form->field($model, 'customerName'),
            'label'     =>  'Адресная доставка',
            'id'        =>  '2'

        ]
    ]
])?>
</div>

<?php
/*$form->field($model, 'payment')->radioList([
    '0' =>  'Наличными при получении (25 от сумы + 20 грн.)',
    '1' =>  'Оплата на карту ПриватБанк (1% от сумы)',
    '2' =>  'Visa / MasterCard (1% от сумы)'
])->label(false);*/

?>

<?=$form->field($model, 'payment', [])->radioList(\common\models\PaymentTypes::getPaymentTypes())->label(false);
?>
<a>Добавить коментарий к заказу</a>