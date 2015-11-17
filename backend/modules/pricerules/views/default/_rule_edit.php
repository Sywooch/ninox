<?php
$form = new \yii\bootstrap\ActiveForm();

$terms = \backend\models\Pricerule::terms();

$termsDropdown = [];

foreach($terms as $term){
    $termsDropdown[] = $term['0'];
}

echo '<pre>';

if(!empty($rule->Formula)){
    //print_r($rule->asArray());
}
echo 'В разработке...';
echo '</pre>';