<?php
$keywords = [];

if(isset($category->keyword) && strlen($category->keyword) >= 1){
    $a = explode(', ', strip_tags($category->keyword));
    if(sizeof($a) >= 1 && $a['0'] != ""){
        foreach($a as $aa){
            $keywords[] = '<span class="label label-success">'.$aa.'</span>';
        }
    }
}
?>
<table class="table table-responsive good-table">
    <tbody>
    <tr>
        <td>Название</td>
        <td><?=$category->Name?></td>
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