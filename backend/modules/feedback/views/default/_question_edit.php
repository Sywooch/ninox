<?php
$form = new \yii\bootstrap\ActiveForm();

$terms = \backend\models\Question::terms();

$termsDropdown = [];

foreach($terms as $term){
    $termsDropdown[] = $term['0'];
}

echo '<pre>';

if(!empty($rule->name)){
    //print_r($rule->asArray());
}
echo 'В разработке...';
echo '</pre>';