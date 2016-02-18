<?php
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;

$this->title = 'Импорт прайслистов';

$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', $this->title);

echo Html::tag('div', \bobroid\fileinput\FileInput::widget([
    'name'          =>  'file',
    'style'         =>  \bobroid\fileinput\FileInput::STYLE_BUTTON,
    'buttonOptions' =>  [
        'new-label'     =>  'Загрузить '.FA::i('plus'),
        'exists-label'  =>  'Загрузить '.FA::i('plus'),
    ]
]), [
    'class' =>  'btn-group',
    'style' =>  'margin-bottom: 10px;'
]);

echo \kartik\grid\GridView::widget([
    'dataProvider'  =>  $priceListsProvider
]);

$remodal = new \bobroid\remodal\Remodal([
    'addRandomToID'     =>  false,
    'confirmButton'     =>  false,
    'cancelButton'      =>  false,
    'id'                =>  'uploadNewPriceList',
    'content'           =>  $this->render('_upload_pricelist_form')
]);

echo $remodal->renderModal();