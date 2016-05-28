<?php
use common\models\Category;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\widgets\ListView;

foreach($breadcrumbs as $b){
    $this->params['breadcrumbs'][] = $b;
}

$css = <<<'STYLE'

.good-table td:first-child{
    font-weight: bold;
    width: 32%;
}

.good-table td:first-child{
    font-weight: bold;
    width: 24%;
}

dl{
    padding: 0px;
    margin: 0px;
}

dl dt{
    float: left;
    margin-right: 6px;
}

.image-thumb-mask{
    max-width: 48%;
    float: left;
    margin-left: 11px;
    margin-bottom: 11px;
    max-height: 153px;
}

.image-thumb-mask:hover .overlay{
    display: block;
}

.image-thumb-mask:nth-child(odd){
    margin-left: 0px;
}

.image-thumb-mask .overlay{
    top: -112px;
    display: none;
    position: relative;
    left: 6px;
    height: 0px;
}

.image-thumb-mask .overlay i{
    font-size: 10px;
    padding: 0;
    margin: 0;
}

.file-preview{
    border: none;
    padding-left: 0px;
}

.file-drop-zone{
    border: none;
    margin-left: 0px;
}

STYLE;

$this->registerCss($css);

$js = <<<'SCRIPT'
var changeVideo = function(code){
    document.querySelector(".video").innerHTML = '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' + code + '" frameborder="0" allowfullscreen></iframe></div>';
}, validateForm = function(e){
    //e.preventDefault();
}

document.querySelector("#video_code_input").addEventListener('blur', function(e){
    if(e.currentTarget.value.replace(/\s+/, '') != ""){
        changeVideo(e.currentTarget.value);
    }
}, false);

document.querySelector("#good-edit-form").addEventListener('submit', function(e){
    validateForm(e);
}, false);

document.querySelector("#undefined_opt_count").addEventListener('change', function(e){
    if(e.currentTarget.checked){
        document.querySelector("#good-num_opt").disabled = true;
        document.querySelector("#good-num_opt").value = "0";
    }else{
        document.querySelector("#good-num_opt").disabled = false;
        document.querySelector("#good-num_opt").value = "1";
    }
}, false);

var changeState = function(e){
    var target = e.currentTarget;
    $.ajax({
        type: 'POST',
        url: '/goods/toggle',
        data: {
            'goodID': e.currentTarget.getAttribute("data-attribute-goodID"),
            'attribute': 'enabled'
        },
        success: function(data){
            if(data.length >= "1"){
                target.innerHTML = data == "1" ? "Отключить" : "Включить";
                if(document.querySelector("#good-enabled") != null && document.querySelector("#good-enabled") != undefined){
                    var el = document.querySelector("#good-enabled");
                    el.querySelector("input[value='" + data + "']").checked = true;
                }
            }
        }
    });
}, changeTrashState = function(e){
    var target = e.currentTarget;
    $.ajax({
        type: 'POST',
        url: '/goods/toggle',
        data: {
            'goodID': e.currentTarget.getAttribute("data-attribute-goodID"),
            'attribute': 'Deleted'
        },
        success: function(data){
            if(data.length >= "1"){
                target.innerHTML = data == "1" ? "Восстановить" : "Удалить";
            }
        }
    });
}, addEventsToRemoveAdditionalPhotoButtons = function(){
    var rmPhoto = function(elem){
        elem.preventDefault();

        var item = elem.currentTarget,
            id = item.getAttribute('data-attribute-id');

        $.ajax({
            type: 'POST',
            url: '/goods/removeadditionalphoto',
            data: {
                'additionalPhotoID': id
            },
            success: function(data){
                item.parentNode.parentNode.remove();
            }
        });
    }

    var a = document.querySelectorAll(".rmAdditionalPhoto");
    for(var i = 0; i < a.length; i++){
        a[i].addEventListener('click', rmPhoto, false);
    }
};

addEventsToRemoveAdditionalPhotoButtons();

document.querySelector("#changeState").addEventListener('click', changeState, false);
document.querySelector("#changeTrashState").addEventListener('click', changeTrashState, false);

SCRIPT;

$this->registerJs($js);

$this->title = $good->Name == "" ? "Добавление товара" : "Товар \"".HTML::decode($good->Name)."\"";
?>
<h1><?=$good->Name == "" ? "Новый товар " : HTML::decode($good->Name)?><?php if(!empty($nowCategory)){ ?> <small><?=$nowCategory->Name?></small><?php } ?></h1>
<?php $form = ActiveForm::begin([
    'id' => 'good-edit-form',
    'fieldConfig' => [
        'template' => "{input}<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
    'options'   =>  [
        'enctype' => 'multipart/form-data'
    ]
]); ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <div class="btn-group pull-left" role="group" aria-label="...">
            <?php if($good->enabled != 0 && $good->Deleted != 1){ ?>
                <a href="https://krasota-style.com.ua/tovar/<?=$good->link?>-g<?=$good->ID?>" class="btn btn-default"><i class="glyphicon glyphicon-globe"></i> Посмотреть на сайте</a>
            <?php } ?>
            <?=\backend\widgets\ChangesWidget::widget([
                'model'         =>  $good,
                'header'        =>  'Изменения по товару '.$good->Name
            ])?>
        </div>


        <button class="btn btn-success pull-right" type="submit" style="margin-left: 10px;">Сохранить</button>
        <div class="btn-group pull-right" role="group" aria-label="...">
            <button type="button" id="changeTrashState" class="btn btn-info" <?=$good->ID == '' ? 'disabled="disabled" ' : ''?>data-attribute-goodID="<?=$good->ID?>"><?=$good->Deleted == "0" ? "Удалить" : "Восттановить";?></button>
            <button type="button" id="changeState" class="btn btn-info" <?=$good->ID == '' ? 'disabled="disabled" ' : ''?>data-attribute-goodID="<?=$good->ID?>"><?=$good->enabled == "1" ? "Отключить" : "Включить";?></button>
            <a href="/goods/view/<?=$good->ID?>" <?=$good->ID == '' ? 'disabled="disabled" ' : ''?>type="button" class="btn btn-info">В режим просмотра</a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-4">
                <?php //TODO: refactor this ?>
                <?php if($good->photo != ''){?><img class="img-thumbnail" id="good-main-photo" src="<?=\Yii::$app->params['cdn-link']?>/img/catalog/<?=$good->photo?>">
                <br style="margin-top: 10px; display: block;">
                <?php } ?>
                <div id="updateGoodPhoto" class="clearfix">
                    <?=FileInput::widget([
                        'name'  =>  'GoodsPhoto[ico]',
                        'options'=>[
                            'accept' => 'image/*'
                        ],
                        'pluginOptions' => [
                            'uploadUrl' =>  '/goods/uploadgoodphoto',
                            'uploadExtraData' => [
                                'ItemId' => $good->ID
                            ],
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => true,
                            'showPreview' => false,
                            'uploadClass'   =>  'btn btn-info',
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' =>  'Выбрать фото',
                            'uploadLabel' =>  'Загрузить',
                            'layoutTemplates'   =>  [
                                'main1' =>  '{preview}\n<div class="kv-upload-progress hide"></div>\n<div class="input-group {class}">\n{caption}\n
                                             <div class="input-group-btn">\n{remove}\n{cancel}\n{browse}\n{upload}\n</div>\n</div>',
                                'main2' =>  '{preview} <div class="kv-upload-progress hide"></div><div class="row"><div class="col-xs-8">{browse}</div><div class="col-xs-4" style="margin-left: -17px;">{upload}</div></div>'
                            ],
                        ],
                        'pluginEvents'  =>  [
                            'fileuploaded'    =>  'function(event, data, previewId, index) {
                                console.log(\'TODO: fix some sheet\');
                            }',
                        ],
                    ])?>
                </div>
                <br>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Прикреплённое видео</h3>
                    </div>
                    <div class="panel-body">
                        <div class="video">
                            <?php if($good->video){ ?>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$good->video?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <?php } ?>
                        </div>
                        <br>
                        <?=$form->field($good, 'video', [
                            'template'  =>  'Код видео: <div class="col-lg-9" style="float: right">{input}<div class=\"col-lg-8\">{error}</div></div>',
                            'inputOptions'  => [
                                'id'    =>  'video_code_input'
                            ]
                        ])?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Дополнительные фото</h3>
                    </div>
                    <div class="panel-body">
                        <?=Collapse::widget([
                            'items' => [
                                [
                                    'label' => 'Добавить фото',
                                    'content' => $form->field(new \common\models\GoodsPhoto, 'ico')->widget(FileInput::className(), [
                                        'options'=>[
                                            'multiple' => true,
                                            'accept' => 'image/*'
                                        ],
                                        'pluginOptions' => [
                                            'uploadUrl' => '/goods/uploadadditionalphoto',
                                            'uploadExtraData' => [
                                                'ItemId' => $good->ID
                                            ],
                                            'showPreview' => true,
                                            'showCaption' => false,
                                            'showRemove' => false,
                                            'showUpload' => true,
                                            'maxFileCount' => 30,
                                            'allowedFilesTypes' =>  ['image'],
                                            'browseLabel'   =>  'Обзор...',
                                            'uploadLabel'   =>  'Загрузить',
                                            'dropZoneTitle'   =>  'Перенесите файлы сюда'
                                        ],
                                        'pluginEvents'  =>  [
                                            'fileuploaded'    =>  'function(event, data, previewId, index){
                                                if(data.length != 0){
                                                    var a = document.createElement("div"),
                                                        b = document.createElement("div"),
                                                        c = document.createElement("div"),
                                                        d = document.createElement("img"),
                                                        e = document.createElement("button");

                                                        a.setAttribute("class", "image-thumb-mask");
                                                        b.setAttribute("class", "image");
                                                        c.setAttribute("class", "overlay");
                                                        d.setAttribute("src", "/img/catalog/" + data.response.link);
                                                        d.setAttribute("class", "img-thumbnail");
                                                        e.setAttribute("class", "btn btn-sm btn-danger");
                                                        e.setAttribute("data-attribute-id", data.response.id);

                                                        e.innerHTML = "<i class=\"glyphicon glyphicon-trash\"></i>";

                                                        c.appendChild(e);
                                                        b.appendChild(d);
                                                        a.appendChild(b);
                                                        a.appendChild(c);

                                                    document.querySelector(".additional-photos").appendChild(a);
                                                }
                                             }'
                                        ],
                                    ]),
                                    'contentOptions' => []
                                ],
                            ]
                        ]);?>
                    </div>
                    <div class="panel-body additional-photos">
                    <?php
                    if(!empty($additionalPhotos)){
                        echo ListView::widget([
                            'dataProvider' => $additionalPhotos,
                            'summary'   =>  '',
                            'itemOptions' => ['class' => 'image-thumb-mask'],
                            'itemView' => function ($model, $key, $index, $widget) {
                                return '<div class="image">
                                <img class="img-thumbnail" src="'.\Yii::$app->params['cdn-link'].'/img/catalog/'.$model->ico.'">
                            </div>
                            <div class="overlay">
                                <button class="btn btn-sm btn-danger rmAdditionalPhoto" data-attribute-id="'.$model->id.'"><i class="glyphicon glyphicon-trash"></i></button>
                            </div>';
                            },
                        ]);
                    }?>
                    </div>
                </div>
            </div>
            <div class="col-xs-8">
                <div class="table-responsive">
                    <div>
                        <?=\kartik\tabs\TabsX::widget([
                            'height'=>\kartik\tabs\TabsX::SIZE_MEDIUM,
                            'items' =>  [
                                [
                                    'label' =>  'Русский язык',
                                    'content'   =>  '<table class="table descr-table">
                            <tr>
                                <td>
                                    Название:
                                </td>
                                <td>
                                    '.$form->field($good, "Name").'
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Описание:
                                </td>
                                <td>
                                    '.bobroid\imperavi\Widget::widget([
                                            'model' => $good,
                                            'attribute' => 'Description',
                                            'options' => [
                                                'toolbar' => true,
                                                /*'autosave'  =>  '#',
                                                'autosaveInterval'  =>  '5',*/
                                                'imageUpload' => '/upload.php',
                                                'imageManagerJson' => '/images/images.json'
                                            ],
                                            'plugins' => [
                                                'fullscreen',
                                                'imagemanager',
                                                'fontcolor',
                                                'fontsize',
                                                'table',
                                            ]
                                        ]).'
                                </td>
                            </tr>
                        </table>'
                                ],[
                                    'label' =>  'Українська мова',
                                    'content'   =>  '<table class="table descr-table">
                            <tr>
                                <td>
                                    Назва:
                                </td>
                                <td>
                                    '.$form->field($goodUk, "Name").'
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Опис:
                                </td>
                                <td>
                                    '.bobroid\imperavi\Widget::widget([
                                            'model' => $goodUk,
                                            'attribute' => 'Description',
                                            'options' => [
                                                'toolbar' => true,
                                                'horizontalrule'    =>  false,
                                                'imageUpload' => '/upload.php',
                                                'imageManagerJson' => '/images/images.json'
                                            ],
                                            'plugins' => [
                                                'fullscreen',
                                                'imagemanager',
                                                'fontcolor',
                                                'fontsize',
                                                'table',
                                            ]
                                        ]).'
                                </td>
                            </tr>
                        </table>'
                                ]
                            ],
                            'encodeLabels'  =>  'false'
                        ]);?>
                        <table class="table good-table">
                            <tr>
                                <td>
                                    Категория:
                                </td>
                                <td>
                                    <?=Select2::widget([
                                        'model' =>  $good,
                                        'attribute' =>  'GroupID',
                                        'data' => Category::getList(),
                                        'options' => [
                                            'placeholder' => 'Выберите категорию...'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false
                                        ],
                                    ])?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Количество:
                                </td>
                                <td>
                                    <?=$form->field($good, 'count', [
                                        'template'  =>  '<div style="margin-left: -15px;" class="col-xs-4">{input}<div class=\"col-lg-8\">{error}</div></div> шт.'
                                    ])?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Привязка к валюте:
                                </td>
                                <td>
                                    <dl>
                                        <dt>Привязка:</dt>
                                        <dd><?=$form->field($good, "anotherCurrencyPeg")->checkbox([
                                                'checked'   => $good->anotherCurrencyPeg == 1,
                                                'template'  =>  '{input}'
                                            ])?></dd>
                                        <dt>Валюта:</dt>
                                        <dd><?=$form->field($good, "anotherCurrencyTag", [
                                                'template'  =>  '<div class="col-xs-4">{input}<div class=\"col-lg-8\">{error}</div></div>'
                                            ])->dropDownList([
                                                'usd' => 'Доллар', 'eur' => 'Евро'
                                            ])?></dd>
                                    </dl>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Цена (в валюте):
                                </td>
                                <td>
                                    <?=$form->field($good, "anotherCurrencyValue", [
                                        'template'  =>  '<div style="margin-left: -15px;" class="col-xs-3">{input}<div class=\"col-lg-8\">{error}</div></div>',
                                        'inputTemplate' =>  '<div class="input-group"><span class="input-group-addon currency-symbol">'.($good->anotherCurrencyTag == "" ? "" : $good->anotherCurrencyTag == "usd" ? " 	&#36;" : "&#8364;").'</span>{input}</div>'
                                    ])?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Цена (опт):
                                </td>
                                <td>
                                    <?=$form->field($good, "PriceOut1", [
                                        'template'  =>  '<div style="margin-left: -15px;" class="col-xs-4">{input}<div class=\"col-lg-8\">{error}</div></div>',
                                        'inputTemplate' =>  '<div class="input-group"><span class="input-group-addon">&#8372;</span>{input}</div>'
                                    ])?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Цена (розница):
                                </td>
                                <td>
                                    <?=$form->field($good, "PriceOut2", [
                                        'template'  =>  '<div style="margin-left: -15px;" class="col-xs-4">{input}<div class=\"col-lg-8\">{error}</div></div>',
                                        'inputTemplate' =>  '<div class="input-group"><span class="input-group-addon">&#8372;</span>{input}</div>'
                                    ])?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Количество в упаковке:
                                </td>
                                <td>
                                    <?=$form->field($good, "num_opt", [
                                        'template'  =>  '<div style="margin-left: -15px;" class="col-xs-4">{input}<div class=\"col-lg-8\">{error}</div></div><input type="checkbox" id="undefined_opt_count"'.($good->num_opt == "" || $good->num_opt == "0" ? " checked" : "").'><label for="undefined_opt_count">&nbsp;Неизвестно</label>',
                                    ])->widget(TouchSpin::classname(), [
                                        'options'   =>  [
                                            'disabled'  =>  ($good->num_opt == "" || $good->num_opt == "0")
                                        ],
                                        'pluginOptions' =>  [
                                            'max'   =>  10000
                                        ]
                                    ]);?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Размеры:
                                </td>
                                <td>
                                    <?=$form->field($good, "gabarity")?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Ширина (см):
                                </td>
                                <td>
                                    <?=$form->field($good, "shyryna")?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Высота (см):
                                </td>
                                <td>
                                    <?=$form->field($good, "vysota")?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Длина (см):
                                </td>
                                <td>
                                    <?=$form->field($good, "dovgyna")?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Диаметр (см):
                                </td>
                                <td>
                                    <?=$form->field($good, "dyametr")?>
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
                                    <?=$form->field($good, "Code")?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Штрихкод товара:
                                </td>
                                <td>
                                    <?=$form->field($good, "BarCode1")?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Дополнительный штрихкод товара:
                                </td>
                                <td>
                                    <?=$form->field($good, "BarCode2")?>
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
                                    <?=$form->field($good, "yandexExport")->checkbox([
                                        'checked'   =>  $good->yandexExport == 1,
                                        'template'  =>  '{input}',
                                    ])?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Оригинальный товар:
                                </td>
                                <td>
                                    <?=$form->field($good, "originalGood")->checkbox([
                                        'checked'   =>  $good->originalGood == 1,
                                        'template'  =>  '{input}',
                                    ])?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Состояние товара:
                                </td>
                                <td>
                                    <?=$form->field($good, 'enabled', [
                                        'inline'    =>  true,
                                        'enableLabel'   =>  false
                                    ])->radioList([
                                        '1' =>  'Включен',
                                        '0' =>  'Выключен'
                                    ], [
                                        'id'    =>  'enabled',
                                        'class' =>  'btn-group',
                                        'data-toggle'   =>  'buttons',
                                        'unselect'  =>  null,
                                        'item'  =>  function ($index, $label, $name, $checked, $value) {
                                            return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
                                            Html::radio($name, $checked, ['value' => $value, 'class' => 'project-status-btn']) . $label . '</label>';
                                        }
                                    ])?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <center>
                <button class="btn btn-lg btn-success" style="margin: 0 auto" type="submit">Сохранить</button>
                или
                <a href="/goods/showgood/<?=$good->ID?>" type="button" class="btn btn-info">В режим просмотра</a>
            </center>
        </div>
    </div>
</div>