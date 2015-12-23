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
<?=
$form->field($model, 'anotherReceiver')->radioList([
    '0' => 'Отправлять на меня',
    '1' => 'Будет получать другой человек'
], [
    'type'  =>  'primary',
    'size'  =>  'default'
])->label(false)?>

<?=$form->field($model, 'payment', [
])->radioList(\common\models\PaymentTypes::getPaymentTypes())->label(false);
?>
<a>Добавить коментарий к заказу</a>