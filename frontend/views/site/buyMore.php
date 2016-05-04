<?=\Yii::t('shop', 'Минимальная сумма заказа: {minimalSum} {currency}', [
    'minimalSum'    =>  \Yii::$app->params['domainInfo']['minimalOrderSum'],
    'currency'      =>  \Yii::$app->params['domainInfo']['currencyShortName']
])?>