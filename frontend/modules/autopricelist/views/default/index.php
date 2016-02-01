<?php
$this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);

echo $this->render('shopInfo_yml', [
        'model' =>  $shop
]);

echo '        <categories>';
foreach($categories as $category){
    echo $this->render('category_yml', [
        'model' =>  $category,
        'categories'    =>  $categoriesByCodes
    ]);
}
echo '
        </categories>
        <offers>';
foreach($items as $item){
    echo $this->render('item_yml', [
        'model' =>  $item,
        'category'  =>  $categories[$item->GroupID]
    ]);
}
echo '
        </offers>';