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

$this->title = 'Импорт из файла "'.$filename.'"';

$allowedColumns = [
    'PriceOut1'             =>  'Цена опт',
    'PriceOut2'             =>  'Цена розничная',
    'anotherCurrencyValue'  =>  'Цена в валюте',
    'anotherCurrencyTag'    =>  'Валюта',
    'GroupID'               =>  'Категория',
    'Name'                  =>  'Название',
    'Code'                  =>  'Код',
    'BarCode1'              =>  'Штрихкод',
    'BarCode2'              =>  'Добавочный код',
    'count'                 =>  'Колличество',
];

$allowedOptions = ['PriceOut1', 'PriceOut2', 'anotherCurrencyValue', 'anotherCurrencyTag', 'GroupID', 'Name', 'Code', 'BarCode1', 'BarCode2', 'count'];

$good = new \backend\models\Good();

//$allowedColumns = array_diff_assoc($allowedOptions, $good->attributeLabels());

?>
<h1><?=$this->title?></h1>
<?php
/*
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

*/

echo Html::beginForm();

for($a = 'A', $i = 0; $a <= $columns; $a++){
    echo Html::tag('div', Html::label('Столбец '.$i, 'forColumn'.$i).'&nbsp;'.Html::dropDownList('PriceListImportTable[columns]['.$i.'][attribute]', null, array_merge(['' => 'Не выбрано'], $allowedColumns), [
            'id'    =>  'forColumn'.$i
    ]).Html::checkbox('PriceListImportTable[columns]['.$i.'][key]', false, ['value' => 1, 'label' => ' ключ']));

    $i++;
}

echo Html::submitButton('Импортировать');

echo Html::endForm();

echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'summary'       =>  false,
    'options'       =>  [
        'class' =>  'table'
    ]
]);