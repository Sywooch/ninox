<?php
use common\helpers\Formatter;
use yii\bootstrap\Html;

$this->title = 'Моя скидка';
$this->params['breadcrumbs'][] = $this->title;

$discount = '';

if(!empty(\Yii::$app->user->identity->cardNumber || !empty(\Yii::$app->user->identity->priceRules))){
    if(!empty(\Yii::$app->user->identity->cardNumber)){
        $discount .= Html::tag('div',
            \Yii::t('shop', 'Дисконтная карта - {discount}% на все товары на сайте', ['discount' => \Yii::$app->user->identity->discount]),
            ['class' => 'user-card-discount']
        );
    }
    foreach(\Yii::$app->user->identity->priceRules as $rule){
        $data = $rule->ruleData;
        $discount .= Html::tag('div',
            Html::tag('div', $data['discount'], ['class' => 'discount blue']).
            Html::tag('div', \Yii::t('shop', 'при покупке от {sum}', ['sum' => $data['sum']]), ['class' => 'sum blue']).
            Html::tag('div', \Yii::t('shop', 'Персональная скидка действует на {categoryCanBuy, plural, =0{все категории} =1{категорию {canLinks}}
                other{категории {canLinks}}}.',
                $data),
                ['class' => 'categories']
            ).
            ($data['categoryCantBuy'] > 0 ? Html::tag('div', \Yii::t('shop', '{categoryCantBuy, plural, =0{} =1{Кроме категории {cantLinks}}
                other{Кроме категорий {cantLinks}}}.',
                $data),
                ['class' => 'not-categories']
            ) : ''),
            ['class' => 'personal-rule']
        );
        break;
    }
}else{
    $discount = \Yii::t('shop', 'Покупатель, который сделает единоразовую покупку на сумму свыше 5 000 грн.,
    становится владельцем персональной дисконтной карточки, и имеет возможность делать последующие
    покупки со скидкой 2% от их стоимости. За более подробной информацией о правилах и условиях получения
    персональной скидки обращайтесь к нашим менеджерам.');
}

echo Html::tag('div',
    Html::tag('div',
        \frontend\widgets\ListGroupMenu::widget([
            'items'    => [
                [
                    'label' =>  \Yii::t('shop', 'Мои заказы'),
                    'href'  =>  '/account/orders'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Личные данные'),
                    'href'  =>  '/account'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Моя скидка'),
                    'href'  =>  '/account/discount'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Список желаний'),
                    'href'  =>  '/account/wish-list'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Мои отзывы'),
                    'href'  =>  '/account/reviews'
                ],
                /*[
                    'label' =>  \Yii::t('shop', 'Возвраты'),
                    'href'  =>  '/account/returns'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Ярмарка мастеров'),
                    'href'  =>  '/account/yarmarka-masterov'
                ],*/
            ]
        ]),
        [
            'class' =>  'menu'
        ]).
    Html::tag('div',
        Html::tag('div',
            Html::tag('div',
                Html::tag('div', \Yii::t('shop', 'Ваша скидка'), ['class' => 'icon icon-your-discont']).
                $discount.
                Html::tag('div',
                    \Yii::t('shop', 'Сума покупок в этом месяце {sum}',
                        ['sum' => Formatter::getFormattedPrice(\Yii::$app->user->identity->monthOrdersSum)]
                    ),
                    ['class' => 'month-orders-sum']
                ),
                ['class' => 'discount-info']
            ).
            Html::tag('div',
                Html::tag('div', \Yii::t('shop', 'Личный счёт'), ['class' => 'icon icon-money-pig']).
                Html::tag('div', \Yii::t('shop', 'На вашем счету:')).
                Html::tag('div', Formatter::getFormattedPrice(\Yii::$app->user->identity->money), ['class' => 'money-amount']),
                ['class' => 'money-info']),
            ['class' => 'money-discount-info clear-fix']
        ).
        Html::tag('div',
            \Yii::t('shop', 'С 16.06.2016 интернет-магазин Krasota-Style запускает систему персональных скидок
            для наших постоянных клиентов (рибейт). Размер скидки, группа товаров, на которую она распространяется,
            и прочие условия указаны в личном кабинете пользователя в разделе - “Скидки”. Спасибо, что Вы с нами,
            впереди Вас ожидает много приятных новшеств!'),
            ['class' => 'temp-info-block']
        ),
        [
            'class' =>  'user-data-content discount'
        ]
    ),
    [
        'class' =>  'content'
    ]
);