<?php
use yii\helpers\Html;

$css = <<<'STYLE'
.hovered{
    background: rgba(255, 255, 170, 0.5);
    border-top: 1px solid rgba(221, 221, 221, 0.5) !important;
    cursor: pointer;
}

STYLE;


$js = <<<'SCRIPT'
    $("table td").click(function(e){
        console.log(e);
        //TODO: сделать что-то, чтобы можно было выбирать столбцы
    });

    $("table td").mouseover(function(e){
        $("table td[data-attribute-column='" + e.currentTarget.getAttribute("data-attribute-column") + "']").addClass("hovered");
    });

    $("table td").mouseout(function(e){
        $("table td[data-attribute-column='" + e.currentTarget.getAttribute("data-attribute-column") + "']").toggleClass("hovered");
    });
SCRIPT;

$this->registerJs($js);
$this->registerCss($css);

$this->params['breadcrumbs'][] = [
    'label' =>  'Импорт из excel',
    'url'   =>  \yii\helpers\Url::toRoute('/goods/import')
];

$this->params['breadcrumbs'][] = $filename;

$this->title = 'Импорт из файла "'.$filename.'"'

?>
<h1><?=$this->title?></h1>
<table class="table">
    <thead>
        <tr></tr>
    </thead>
<?php
foreach($xls as $row){
    $column = 0;
    echo '<tr>';
    foreach($row as $col){
        if(!empty($col)){
            echo Html::tag('td', $col, [
                'data-attribute-column' =>  $column,
            ]);
            $column++;
        }
    }
    echo '</tr>';
}
?>
</table>