<?php
use yii\helpers\Html;
use yii\widgets\ListView;


$css = <<<'STYLE'

.good-table td:first-child{
    font-weight: bold;
    width: 32%;
}

dl{
    padding: 0px;
    margin: 0px;
}

dl dt{
    float: left;
    margin-right: 6px;
}

STYLE;

$js = <<<'JS'
var hideAdditionalPhotos = function(){
    var a = document.querySelectorAll("#additionalPhotos div.image-thumb-mask"),
        elem = document.createElement('div'),
        button = document.createElement('button');

    if(a.length > 2){
        elem.setAttribute("id", "collapse");
        elem.setAttribute("class", "collapse");

        button.setAttribute('class', 'btn btn-primary btn-block');
        button.setAttribute('onclick', 'this.remove();');
        button.setAttribute('type', 'button');
        button.setAttribute('data-toggle', 'collapse');
        button.setAttribute('data-target', '#collapse');
        button.setAttribute('aria-expanded', 'false');
        button.setAttribute('aria-controls', 'collapse');
        button.textContent = 'Ещё изображения (' + (a.length - 2) + ')';

        document.querySelector("#additionalPhotos > div").appendChild(button);
        document.querySelector("#additionalPhotos > div").appendChild(elem);
    }

    for(var i = 0; i < a.length; i++){
        if(i >= 2){
            document.querySelector("#additionalPhotos #collapse").appendChild(a[i]);
        }
    }
};

hideAdditionalPhotos();
JS;

$this->registerCss($css);
$this->registerJs($js);

$this->title = 'Товар "'.$good->Name.'"';
?>
<h1><?=$good->Name?> <?=isset($good->category) ? Html::tag('small', $good->category->Name) : ''?></h1>
<div class="panel panel-info">
    <div class="panel-heading">
        <?=$this->render('_showgood_heading', [
            'good'  =>  $good
        ])?>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-4">
                <img class="img-thumbnail" src="<?=\Yii::$app->params['cdn-link']?>/img/catalog/<?=$good->photo?>">
                <br><br>
                <?php if(!empty($good->video)){ ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Прикреплённое видео</h3>
                    </div>
                    <div class="panel-body">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$good->video?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <?php }
                if(!empty($additionalPhotos)){
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Дополнительные фото</h3>
                    </div>
                    <div class="panel-body" id="additionalPhotos">
                    <?=ListView::widget([
                        'dataProvider' => $additionalPhotos,
                        'itemOptions' => ['class' => 'image-thumb-mask'],
                        'summary'   =>  '',
                        'itemView' => function ($model) {
                            return Html::img(\Yii::$app->params['cdn-link'].'/img/catalog/'.$model->ico, [
                                'class' =>  'img-thumbnail',
                                'style' =>  'margin-bottom: 15px'
                            ]);
                        },
                    ])?>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
            <div class="col-xs-8">
                <div class="table-responsive">
                    <div>
                        <?=\kartik\tabs\TabsX::widget([
                            'height'=>\kartik\tabs\TabsX::SIZE_SMALL,
                            'items' =>  [
                                [
                                    'label' =>  'Русский язык',
                                    'content'   =>  '<table class="table good-table">
                            <tr>
                                <td>
                                    Название:
                                </td>
                                <td>
                                    '.$good->Name.'
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Описание:
                                </td>
                                <td>
                                    '.$good->Description.'
                                </td>
                            </tr>
                        </table>'
                                ],[
                                    'label' =>  'Українська мова',
                                    'content'   =>  '<table class="table good-table">
                            <tr>
                                <td>
                                    Назва:
                                </td>
                                <td>
                                    '.$goodUk->Name.'
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Опис:
                                </td>
                                <td>
                                    '.$goodUk->Description.'
                                </td>
                            </tr>
                        </table>'
                                ]
                            ],
                            'encodeLabels'  =>  'false'
                        ]);?>
                        <table class="table good-table">
                            <?php
                            if(is_object($good->category)){
                            ?>
                            <tr>
                                <td>
                                    Категория:
                                </td>
                                <td>
                                    <a href="/categories/view/<?=$good->category->ID?>"><?=$good->category->Name?></a>
                                </td>
                            </tr>
                            <?php
                            }

                            ?>
                            <tr>
                                <td>
                                    Количество:
                                </td>
                                <td>
                                    <?=$good->count?> шт.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Привязка к валюте:
                                </td>
                                <td>
                                    <dl>
                                        <dt>Привязка:</dt>
                                        <dd><input type="checkbox" disabled readonly<?=$good->anotherCurrencyPeg == 1 ? " checked" : ""?>></dd>
                                        <dt>Валюта:</dt>
                                        <dd><?=$good->anotherCurrencyTag?></dd>
                                    </dl>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Цена (в валюте):
                                </td>
                                <td>
                                    <?=$good->anotherCurrencyValue == "" ? 0 : $good->anotherCurrencyValue?> <?=$good->anotherCurrencyTag?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Цена (опт):
                                </td>
                                <td>
                                    <?=$good->PriceOut1?> грн.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Цена (розница):
                                </td>
                                <td>
                                    <?=$good->PriceOut2?> грн.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Количество в упаковке:
                                </td>
                                <td>
                                    <?=$good->num_opt == "0" || $good->num_opt == "" ? "Неизвестно" : $good->num_opt.' '.$good->measure?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Размеры:
                                </td>
                                <td>
                                    <?=$good->dimensions?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Ширина (см):
                                </td>
                                <td>
                                    <?=$good->width?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Высота (см):
                                </td>
                                <td>
                                    <?=$good->height?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Длина (см):
                                </td>
                                <td>
                                    <?=$good->length?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Диаметр (см):
                                </td>
                                <td>
                                    <?=$good->diameter?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Код товара:
                                </td>
                                <td>
                                    <?=$good->Code?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Штрихкод товара:
                                </td>
                                <td>
                                    <?=$good->BarCode1?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Дополнительный штрихкод товара:
                                </td>
                                <td>
                                    <?=$good->BarCode2?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Яндекс каталог:
                                </td>
                                <td>
                                    <input type="checkbox" disabled readonly<?=$good->yandexExport == 1 ? " checked" : ""?>>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Оригинальный товар:
                                </td>
                                <td>
                                    <input type="checkbox" disabled readonly<?=$good->originalGood == 1 ? " checked" : ""?>>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Состояние товара:
                                </td>
                                <td>
                                    <?=$good->Deleted == 1 ? "удалён" : ($good->enabled == 1 ? "включен" : "выключен")?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>