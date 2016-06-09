<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 01.06.16
 * Time: 14:00
 */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = \Yii::t('shop', 'Востановление пароля');
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('div',
    \Yii::t('shop', 'Письмо отправлено Вам на {email}',
        [
            'email' => Html::a(\Yii::t('shop', 'почту'), Url::to(
                'http://'.substr($model->email, strrpos($model->email, '@')+1)),
                [
                    'target' => '_blank'
                ]
            )
        ]
    ),
    [
        'class' => 'content password-recovery-success'
    ]
);