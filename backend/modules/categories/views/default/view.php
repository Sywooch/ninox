<?php
$css = <<<'STYLE'
.tab-content .good-table tr:first-child td{
    border-top: 0px;
}

.good-table td:first-child{
    font-weight: bold;
    width: 23%;
}


dl{
    padding: 0px;
    margin: 0px;
}

dl dt{
    float: left;
    margin-right: 6px;
}

.tab-content{
    padding: 0;
}
STYLE;

$keywords = [];

if(isset($category->keyword) && strlen($category->keyword) >= 1){
    $a = explode(', ', strip_tags($category->keyword));
    if(sizeof($a) >= 1 && $a['0'] != ""){
        foreach($a as $aa){
            $keywords[] = '<span class="label label-success">'.$aa.'</span>';
        }
    }
}

$this->registerCss($css);
?>
<h1><?=$category->name?>&nbsp;<small>Категория</small></h1>
<div class="panel panel-info">
    <div class="panel-heading">
        <div class="btn-group pull-left">
            <?php if($category->enabled != 0){ ?>
                <a href="https://krasota-style.com.ua/<?=$category->link?>" class="btn btn-default"><i class="glyphicon glyphicon-globe"></i> Посмотреть на сайте</a>
            <?php } ?>
            <?=\yii\helpers\Html::a('<i class="glyphicon glyphicon-th-large"></i> Товары категории', \yii\helpers\Url::toRoute(['/categories', 'category' => $category->Code, 'onlyGoods' => true]), ['class' => 'btn btn-default'])?>
            <?=\backend\widgets\ChangesWidget::widget([
                'model'         =>  $category,
                'header'        =>  'Изменения по категории '.$category->name
            ])?>
        </div>
        <div class="btn-group pull-right" role="group" aria-label="...">
            <button type="button" class="btn btn-info" data-attribute-categoryID="<?=$category->ID?>"><?=$category->canBuy == "1" ? "Не продавать товары" : "Продавать товары"?></button>
            <button type="button" class="btn btn-info" data-attribute-categoryID="<?=$category->ID?>"><?=$category->enabled == "1" ? "Выключить" : "Включить"?></button>
            <a href="?act=edit" type="button" class="btn btn-info">Редактировать</a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php if($category->cat_img != '' || sizeof($subCats) >= 1){ ?>
            <div class="col-xs-4">
                <?php if($category->cat_img != ''){ ?>
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <img class="img-thumbnail" src="<?=\Yii::$app->params['cdn-link']?>/img/catalog/<?=$category->cat_img?>">
                        </div>
                        <div class="panel-footer">
                            Фотография категории
                        </div>
                    </div>
                <?php }
                if(sizeof($subCats) >= 1){ ?>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Подкатегории
                        </div>
                        <div class="panel-body">
                            <?php foreach($subCats as $sc){ ?>
                            <a class="list-group-item <?=$sc->enabled == "1" ? "list-group-item-success" : "list-group-item-danger"?>" href="/category/view/<?=$sc->ID?>"><?=$sc->name?></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-xs-8">
            <?php }else{
            ?>
            <div class="col-xs-12">
            <?php
            } ?>
                <div class="table-responsive">
                    <table class="table table-responsive good-table">
                        <tbody>
                        <tr>
                            <td>Название</td>
                            <td><?=$category->name?></td>
                        </tr>
                        <tr>
                            <td><abbr title="Title категории">Тег "title"</abbr></td>
                            <td><?=$category->title?></td>
                        </tr>
                        <tr>
                            <td><abbr title="SEO для title">SEO для тега "title"</abbr></td>
                            <td>
                                <dl>
                                    <dt>"Дёшево":</dt>
                                    <dd><?=$category->titleasc == "" ? "-" : $category->titleasc?></dd>
                                    <dt>"Дорого":</dt>
                                    <dd><?=$category->titledesc == "" ? "-" : $category->titledesc?></dd>
                                    <dt>"Новинки":</dt>
                                    <dd><?=$category->titlenew == "" ? "-" : $category->titlenew?></dd>
                                </dl>
                            </td>
                        </tr>
                        <tr>
                            <td><abbr title="H1 категории">Тег "H1"</abbr></td>
                            <td><?=$category->h1?></td>
                        </tr>
                        <tr>
                            <td><abbr title="SEO для H1">SEO для тега "H1"</abbr></td>
                            <td>
                                <dl>
                                    <dt>"Дёшево":</dt>
                                    <dd><?=$category->h1asc == "" ? "-" : $category->h1asc?></dd>
                                    <dt>"Дорого":</dt>
                                    <dd><?=$category->h1desc == "" ? "-" : $category->h1desc?></dd>
                                    <dt>"Новинки":</dt>
                                    <dd><?=$category->h1new == "" ? "-" : $category->h1new?></dd>
                                </dl>
                            </td>
                        </tr>
                        <tr>
                            <td>Описание</td>
                            <td>
                                <div style="max-height: 280px; overflow-y: auto;">
                                    <?=\yii\helpers\Html::decode($category->descr)?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Текст</td>
                            <td>
                                <div style="max-height: 280px; overflow-y: auto;">
                                    <?=\yii\helpers\Html::decode($category->text2)?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Ключевые слова</td>
                            <td><?php if(sizeof($keywords) >= 1){ echo implode(' ', $keywords); } ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-responsive good-table">
                        <tbody>
                            <?php if(!empty($parentCategory)){
                            ?>
                            <tr>
                                <td>Категория-родитель</td>
                                <td><a href="/categories/view/<?=$parentCategory->ID?>"><?=$parentCategory->name?></a></td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td>Состояние категории</td>
                                <td><?=$category->enabled == "1" ? "Включена" : "Выключена"?>, товары категории <?=$category->canBuy == "1" ? "продаются" : "не продаются"?></td>
                            </tr>
                            <tr>
                                <td>Дополнительно</td>
                                <td>
                                    <dl>
                                        <dt>Одна цена на все товары категории</dt>
                                        <dd><input type="checkbox" <?=$category->onePrice == "1" ? 'checked' : ''?> disabled readonly></dd>
                                        <dt>Категория экспортируется в Яндекс.Каталог</dt>
                                        <dd><input type="checkbox" <?=$category->ymlExport == "1" ? 'checked' : ''?> disabled readonly></dd>
                                    </dl>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>