<?php
    use yii\helpers\Html;

    $messageParts = [];

    $myMessage = $model->author == \Yii::$app->user->identity->id;

    $messageParts[] = Html::tag('span', \Yii::$app->formatter->asDatetime($model->timestamp, 'php: H:i'), [
        'class' =>  'message-data-time'
    ]);

    $messageParts[] = Html::tag('span', ($myMessage ? \Yii::$app->user->identity->name : Html::a(\common\models\Siteuser::getUser($model->author)->name, \yii\helpers\Url::to('/users/showuser/'.$model->author))), [
        'class' =>  'message-data-name'
    ]);

    if(!$myMessage){
        $messageParts = array_reverse($messageParts);
    }
?>
<?=Html::tag('div', implode('&nbsp; &nbsp;', $messageParts), [
    'class' =>  'message-data '.($myMessage ? 'align-right' : '')
])?>
<?=Html::tag('div', $model->text, [
    'class' =>  'message '.($myMessage ? 'other-message float-right' : 'my-message')
])?>