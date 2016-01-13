<div class="managersButtons">
    <?php foreach($managers as $key => $manager){
        echo \yii\bootstrap\Html::button($manager, [
            'manager-key'  =>  $key,
            (\Yii::$app->request->cookies->getValue('cashboxManager', 0) == $key ? 'disabled' : 'enabled') => 'disabled',
            'class' =>  \Yii::$app->request->cookies->getValue('cashboxManager', 0) == $key ? 'cancel' : 'confirm'
        ]);
    }?>
</div>

