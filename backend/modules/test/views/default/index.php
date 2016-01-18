<?php

echo \bobroid\messenger\Messenger::widget([]);?>
<pre>
<?php
$modules = [];
$ignoredModules = ['debug', 'gii', 'gridview'];

foreach(\Yii::$app->modules as $key => $module){
    if(!in_array($key, $ignoredModules)){
        if(is_object($module)){
            $modules[$key] = $module->module->controller->module->controllerNamespace;
        }elseif(is_array($module) && isset($module['class'])){
            $modules[$key] = $module['class'];
        }else{
            $modules[$key] = $module;
        }
    }
}

foreach($modules as $key => $module){
    $module = preg_replace('/\\Module/', '', $module);

    preg_match('/controllers/', $module, $hasController);

    $hasController = sizeof($hasController) >= 1;

    if(!$hasController){
        $module .= 'controllers';
    }

    $module .= '\DefaultController';

    $modules[$key] = $module;
}

print_r($modules);

echo \Yii::$app->controller->className();
echo '<br>';

echo Yii::$app->user->identity->can([
    '1' =>  'can 1',
    '2' =>  'can 2',
    '3' =>  'can 3',
    '4' =>  'can 4',
    '5' =>  'can 5',
]);
?>
    </pre>


<?=\bobroid\messenger\Messenger::widget([

])?>

<?php

$js = <<<'SCRIPT'
Messenger.options = {
    extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
    theme: 'air'
}

Messenger().post("Your request has succeded!");
SCRIPT;

$this->registerJs($js);
