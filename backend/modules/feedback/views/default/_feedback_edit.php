<?php
$form = new \yii\bootstrap\ActiveForm();

$terms = \backend\models\feedback::terms();

$termsDropdown = [];

foreach($terms as $term){
    $termsDropdown[] = $term['0'];
}

echo '<pre>';

if(!empty($feedback->name)){
    //print_r($rule->asArray());
}
echo 'В разработке...';
echo '</pre>';