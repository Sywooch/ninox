<?php
use yii\helpers\Html;

$js = <<<'SCRIPT'
$('label.tabsLabels').click(function () {
    $(this).tab('show');
});
SCRIPT;

$this->registerJs($js);

$items = [];

?>
<div class="content-data-body-delivery-type">
<?=$form->field($model, 'deliveryType', [
    ])->radioList($domainConfiguration, [
        'item' => function($index, $label, $name, $checked, $value) use (&$items){
	        $items[] = [
		        'content'   =>  '<div class="content-data-body-address">
									Мои адреса:
								</div>',
		        'label'     =>  'Адресная доставка',
		        'id'        =>  '',
		        'active' => $checked
	        ];
            return '<div class="tab">'.Html::radio($name, $checked, [
                'value'     =>      $value,
                'id'        =>      "tab-".$index
            ])
            .'<label class="tabsLabels" data-target="#w0-tab'.$index.'" for="tab-'.$index.'">'.$label['name'].'</label></div>';
        }
    ])->label(false)?>
    <?=\yii\bootstrap\Tabs::widget([
        'headerOptions' =>  [
            'style' =>  'display: none'
        ],
        'items' =>  $items
/*            [
                'content'   =>  '<div class="content-data-body-address">
                                        Мои адреса:
                                 </div>',
                'label'     =>  'Адресная доставка',
                'id'        =>  '',
                'active' => true

            ],
            [
                'content'   =>  '<div class="content-data-body-department">
                                        Отделение:
                                        <a id="go" href="#">
                                            <div class="map-icon">
                                            </div>
                                            Cм. на карте
                                        </a>
                                 </div>',
                'label'     =>  'Новая Почта',
                'id'        =>  ''
            ],
            [
                'content'   =>  '<div>
                                <div class="content-data-body-stock">
                                    <div class="semi-bold">Наш склад находится по адресу:</div>
                                    г. Киев, ул. Электротехническая, 2
                                     <a id="go" href="#">
                                            <div class="map-icon">
                                            </div>
                                            Cм. на карте
                                     </a>
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
            ],*/
    ])?>
</div>

<div class="content-data-body-delivery-type">
<?=$form->field($model, 'anotherReceiver')->radioList([
    '0' =>  'Отправлять на меня',
    '1' =>  'Будет получать другой человек',
    ],
    [
        'item'  =>  function ($index, $label, $name, $checked, $value) {
                echo Html::radio($name, $checked, [
                        'value' => $value,
                        'id' => $value
                    ])
                    . '<label class="tabsLabels" data-target="#w1-tab'.$value.'" for="'.$value.'">'.$label.'</label>';
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
            'content'   =>  '<div class="content-data-body-first">'.
                                $form->field($model, 'anotherReceiverName').$form->field($model, 'anotherReceiverSurname').$form->field($model, 'anotherReceiverPhone').
                            '</div>',
            'label'     =>  '',
            'id'        =>  '2'


        ]
    ]
])?>
</div>
<div class="payment-type">Способ оплаты</div>
<?/*=$form->field($model, 'paymentType', [])->radioList(\common\models\PaymentTypes::getPaymentTypes(), [
    'item' => function ($index, $label, $name, $checked, $value) {
        return '<div class="tab">'. Html::radio($name, $checked, [
            'value'     =>      $value,
            'id'        =>      "radio-".$value,
        ])
        .'<label for="radio-'.$value.'"><i></i><div class="payment-type-text">'. $label .'</div></label>

        <div class="question">
                            <div class="round-button">
                                <div class="content-data-title-img">
                                    <a class="round-button" data-toggle="tooltip" data-title="Эта сумма может измениться, в случае если вдруг не будет товаров на складе">?</a>                                </div>
                            </div>
                        </div>


        </div>';
    }
])->label(false);
*/?>
<div class="add-comment">Добавить коментарий к заказу</div>