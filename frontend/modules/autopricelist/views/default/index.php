<?php
use yii\helpers\Html;

$this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);

$renderCategories = [];

foreach($categories as $category){
    $categoryOptions = [
        'id'    =>  $category->ID
    ];

    $parentCode = substr($category->Code, 0, -3);

    if(isset($categoriesByCodes[$parentCode])){
        $categoryOptions['parentId'] = $categoriesByCodes[$parentCode]->ID;
    }

    $renderCategories[] = Html::tag('category', $category->Name, $categoryOptions);
}

echo $this->render('shopInfo_yml', [
        'model' =>  $shop
]);

echo Html::tag('categories', implode('', $renderCategories));
echo \yii\widgets\ListView::widget([
    'summary'       =>  false,
    'layout'        =>  '{items}',
    'id'            =>  false,
    'options'       =>  [
        'tag'       =>  'offers',
    ],
    'itemView'   =>  function($model) use($categories){
        $vendorModel = false;

        $soptions = [];

        if(!empty($model->vendor) && !empty($model->model)){
            $vendorModel = true;
            $soptions['type'] = 'vendor.model';
        }

        $offer = [
            Html::tag('url', 'http://krasota-style.com.ua/tovar/'.$model->link.'-g'.$model->ID),
            Html::tag('price', $model->PriceOut2),
            Html::tag('currencyId', 'UAH'),
            Html::tag('categoryId', $model->GroupID),
            Html::tag('market_category', $categories[$model->GroupID]->yandexName),
            Html::tag('picture', 'http://krasota-style.com.ua/img/catalog/'.$model->ico),
            Html::tag('description', preg_replace('/&\S+;/m', '', strip_tags($model->Description))),
            Html::tag('manufacturer_warranty', 'true'),
            Html::tag('seller_warranty', 'P1Y')
        ];

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

        if(!empty($model->country)){
            $offer[] = Html::tag('country_of_origin', $model->country);
        }

        if(!empty($model->weight)){
            $offer[] = Html::tag('weight', $model->weight);
        }

        if(!empty($model->dimensions)){
            $offer[] = Html::tag('dimensions', $model->dimensions);
        }

        $soptions = array_merge($soptions, ['available' => 'true', 'id'    =>  $model->ID]);

        $offer = Html::tag('offer', '
                '.implode('
                ', $offer).'
            ', $soptions);

        return $offer;
    },
    'itemOptions'   =>  [
        'tag'   =>  false
    ],
    'dataProvider'  =>  $itemsDataProvider
]);