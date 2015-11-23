<div>
    <p class="bg-info">Контроллер модуля: <?=\Yii::$app->controller->className()?></p>
    <p class="bg-info">Текущий экшн: <?=\Yii::$app->controller->action->uniqueId?></p>
    <p class="bg-info">Список экшнов модуля: <?=''//print_r(\Yii::$app->controller)?></p>
</div>