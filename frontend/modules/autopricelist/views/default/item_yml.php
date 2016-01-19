
            <?php
use yii\helpers\Html;

$vendorModel = false;

if(!empty($model->vendor) && !empty($model->model)){
    $vendorModel = true;
    $soptions['type'] = 'vendor.model';
}

$offer = [];
$offer[] = Html::tag('url', 'http://'.$_SERVER['SERVER_NAME'].'/tovar/'.$model->link.'-g'.$model->ID);
$offer[] = Html::tag('price', $model->PriceOut2);
$offer[] = Html::tag('currencyId', 'UAH');
$offer[] = Html::tag('categoryId', $model->GroupID);
$offer[] = Html::tag('market_category', $category->yandexName);
$offer[] = Html::tag('picture', $model->ico);

/*if(!empty($model->has)){
    $offer[] = Html::tag('picture', $addPhoto->value);
}*/

if($vendorModel){
    $offer[] = Html::tag('typePrefix');
    $offer[] = Html::tag('vendor');
    $offer[] = Html::tag('model');
}else{
    $offer[] = Html::tag('name', $model->Name);
}

$offer[] = Html::tag('description', preg_replace('/&\S+;/m', '', htmlspecialchars(strip_tags($model->Description))));
$offer[] = Html::tag('manufacturer_warranty', 'true');
$offer[] = Html::tag('seller_warranty', 'P1Y');

if(!empty($model->country)){
    $offer[] = Html::tag('country_of_origin', $model->country);
}

if(!empty($model->weight)){
    $offer[] = Html::tag('weight', $model->weight);
}


if(!empty($model->dimensions)){
    $offer[] = Html::tag('dimensions', $model->dimensions);
}

$soptions = ['available' => 'true', 'id'    =>  $model->ID];
?>
<?=Html::tag('offer', '
                '.implode('
                ', $offer).'
            ', $soptions)?>