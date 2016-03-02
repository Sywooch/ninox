<?php
$this->title = "Товары";

if(!empty($nowCategory)){
    $this->params['breadcrumbs'][] = [
        'label' =>  'Категории',
        'url'   =>  '/goods'
    ];
}else{
    $this->params['breadcrumbs'][] = $this->title;
}

foreach($breadcrumbs as $b){
    $this->params['breadcrumbs'][] = $b;
}

if(!empty($nowCategory)){
    $this->params['breadcrumbs'][] = $nowCategory->Name;
}

?>
<h1><?=$this->title?><?php if(!empty($nowCategory)){ ?>&nbsp;<small><?=$nowCategory->Name?></small><?php } ?></h1>
<div class="jumbotron well well-lg">
    <div class="container">
        <h1>Нет товаров</h1>
        <p>Товары, в этой категории или в этом статусе, отстутствуют.</p>
    </div>
</div>