<div class="managersButtons">
    <?php foreach($managers as $key => $manager){
        echo \yii\bootstrap\Html::button($manager, [
            'manager-key'  =>  $key,
            ($cashbox->responsibleUser == $key ? 'disabled' : 'enabled') => 'disabled',
            'class' =>  $cashbox->responsibleUser == $key ? 'cancel' : 'confirm'
        ]);
    }?>
</div>

