<?php
$form = new \yii\widgets\ActiveForm([
    'fieldConfig'   =>  [
        'options'   =>  [
            'class' =>  'col-xs-6'
        ]
    ]
]);

$js = <<<'JS'
    $('#autologinList').addInputArea();
JS;

$css = <<<'CSS'
#autologinList li{
    margin-bottom: 5px;
    line-height: 22px;
    vertical-align: top;
}

#autologinList li button{
    margin-top: -2px;
}
CSS;

$this->registerCss($css);

$this->registerJs($js);

$form->begin();
echo \yii\bootstrap\Html::tag('h2', 'Добавить кассу', [
    'style' =>  'margin-top: 0'
]);

echo $form->field($model, 'name'),
    $form->field($model, 'defaultCustomer'),
    $form->field($model, 'domain'),
    $form->field($model, 'defaultWholesaleCustomer');
    echo \yii\bootstrap\Html::tag('hr');
    echo \yii\bootstrap\Html::tag('h3', 'Автологин');
?>
<style>

</style>
    <ol id="autologinList">
        <li class="autologinList_var">
            <input type="text" size="20" placeholder="Логин" name_format="CashboxForm[autologin][%d][username]" name="CashboxForm[autologin][0][username]">
            <input type="text" size="20" placeholder="IP" name_format="CashboxForm[autologin][%d][ip]" name="CashboxForm[autologin][0][ip]">
            <button type="button" class="autologinList_del btn btn-danger btn-sm"><?=\rmrevin\yii\fontawesome\FA::icon('times')?></button>
        </li>
    </ol>
    <button type="button" class="autologinList_add btn btn-success btn-sm"><?=\rmrevin\yii\fontawesome\FA::icon('plus')?> Добавить</button>
<?php
$form->end();