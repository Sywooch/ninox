<?php
use common\models\Category;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = $category->isNewRecord == "" ? "Новая категория" : "Категория \"".$category->Name.'"';

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

$js = <<<'JS'
$(".recalcCategoryPrices").on('click', function(){
    $.ajax({
        url: '/categories/recalc?act=retailPrice',
        method: 'POST',
        data: {
            categoryID: $("#categoryID")[0].getAttribute("data-categoryID"),
            size: $(this.parentNode).find("input").val()
        }, success: function(){
            Messenger().post({
                        message: 'Цены на товары в категории пересчитаны!',
                        type: 'info',
                        showCloseButton: true,
                        hideAfter: 300,
                        actions: {
                            expand: {
                                label: 'к товарам',
                                action: function(){
                                    location.href = '/goods?category=' + data.categoryCode;
                                }
                            },
                            close: {
                                label: 'Закрыть'
                            }
                        }
                    });
        }
    })
})
JS;

$this->registerJs($js);

$this->registerCss($css);

$form = ActiveForm::begin([
    'id' => 'good-edit-form',
    'validateOnBlur'    =>  true,
    'enableAjaxValidation'    =>  true,
    'enableClientValidation' => true,
    'validateOnSubmit' => true,
    'fieldConfig' => [
        'template' => "{input}<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
    'options'   =>  [
        'enctype' => 'multipart/form-data'
    ]
]);
?>
<h1 id="categoryID" data-categoryID="<?=$category->ID?>"><?=!$category->isNewRecord ? $category->Name : 'Новая категория'?>&nbsp;<small>Категория</small></h1>
<div class="panel panel-info">
    <div class="panel-heading">
        <div class="btn-group pull-left">
            <?php if($category->enabled != 0){ ?>
                <a href="https://krasota-style.com.ua/<?=$category->link?>" class="btn btn-default"><i class="glyphicon glyphicon-globe"></i> Посмотреть на сайте</a>
            <?php } ?>
            <?=\backend\widgets\ChangesWidget::widget([
                'model'         =>  $category,
                'header'        =>  'Изменения по категории '.$category->Name
            ])?>
        </div>
        <button class="btn btn-success pull-right" type="submit" style="margin-left: 10px;">Сохранить</button>
        <div class="btn-group pull-right" role="group" aria-label="...">
            <button type="button" class="btn btn-info" data-attribute-categoryID="<?=$category->ID?>"><?=$category->canBuy == "1" ? "Не продавать товары" : "Продавать товары"?></button>
            <button type="button" class="btn btn-info" data-attribute-categoryID="<?=$category->ID?>"><?=$category->enabled == "1" ? "Выключить" : "Включить"?></button>
            <a href="/goods/showcategory/<?=$category->ID?>" type="button" class="btn btn-info">В режим просмотра</a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-4">
                <?php if($category->cat_img != ''){?><img class="img-thumbnail" id="good-main-photo" src="http://krasota-style.com.ua/img/catalog/<?=$category->cat_img?>">
                 <br style="margin-top: 10px; display: block;">
                <?php } ?>
                <div id="updateGoodPhoto" class="clearfix">
                    <?=FileInput::widget([
                        'name'  =>  'categoryPhoto',
                        'options'=>[
                            'accept' => 'image/*'
                        ],
                        'pluginOptions' => [
                            'uploadUrl' =>  '/goods/uploadcategoryphoto',
                            'uploadExtraData' => [
                                'ItemId' => $category->ID
                            ],
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => true,
                            'showPreview' => false,
                            'uploadClass'   =>  'btn btn-info',
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' =>  'Сменить фото',
                            'uploadLabel' =>  'Загрузить',
                            'layoutTemplates'   =>  [
                                'main1' =>  '{preview}\n<div class="kv-upload-progress hide"></div>\n<div class="input-group {class}">\n{caption}\n
                                             <div class="input-group-btn">\n{remove}\n{cancel}\n{browse}\n{upload}\n</div>\n</div>',
                                'main2' =>  '{preview} <div class="kv-upload-progress hide"></div><div class="row"><div class="col-xs-8">{browse}</div><div class="col-xs-4" style="margin-left: -17px;">{upload}</div></div>'
                            ],
                        ],
                        'pluginEvents'  =>  [
                            'fileuploaded'    =>  'function(event, data, previewId, index) {

                            }',
                        ],
                    ])?>
                </div>
            </div>
            <div class="col-xs-8">
                <div class="table-responsive">
                    <?=\yii\bootstrap\Tabs::widget([
                        'items' => [
                            [
                                'label' =>  'Русский язык',
                                'content'   =>  $this->render('_edit_catinfo', [
                                    'category'  =>  $category,
                                    'form'      =>  $form
                                ])
                            ],
                            [
                                'label' =>  'Українська мова',
                                'content'   =>  $this->render('_edit_catinfo', [
                                    'category'  =>  $categoryUk,
                                    'form'      =>  $form
                                ])
                            ]
                        ]
                    ]);
                    ?>
                    <table class="table table-responsive good-table">
                        <tbody>
                            <tr>
                                <td>Категория-родитель</td>
                                <td>
                                    <div style="margin-left: -10px;" class="col-xs-12">
                                        <?=Select2::widget([
                                            'name'  =>  'parent_category',
                                            'value' => isset($parentCategory->ID) ? $parentCategory->ID : '',
                                            'data' => Category::getList(),
                                            'options' => ['placeholder' => 'Выберите категорию...'],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ])?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Состояние категории</td>
                                <td>
                                    <?=$form->field($category, 'menu_show', [
                                        //'inline'    =>  true,
                                        //'enableLabel'   =>  false
                                    ])->radioList([
                                        '1' =>  'Включена',
                                        '0' =>  'Выключена'
                                    ], [
                                        'id'    =>  'menu_show',
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
                            <tr>
                                <td>Розничная цена больше оптовой на</td>
                                <td>
                                    <?=$form->field($category, 'retailPercent', [
                                        //'inline'    =>  true,
                                        //'enableLabel'   =>  false,
                                        'options'   =>  [
                                            'class' =>  'col-xs-5',
                                            'style' =>  'margin-left: -15px'
                                        ],
                                        'addon' =>  [
                                            'append' => [
                                                'content'   =>  '%'
                                            ]
                                        ]
                                    ]),
                                    Html::button('Пересчитать товары категории', ['class' => 'recalcCategoryPrices btn btn-default btn-sm', 'style' => 'margin-top: 2px'])?>

                                </td>
                            </tr>
                            <tr>
                                <td>Одна цена на категорию</td>
                                <td>
                                    <?=$form->field($category, 'onePrice', [
                                        //'inline'    =>  true,
                                        //'enableLabel'   =>  false
                                    ])->radioList([
                                        '1' =>  'Включена',
                                        '0' =>  'Выключена'
                                    ], [
                                        'id'    =>  'onePrice',
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
                            <tr>
                                <td>Категория экспортируется в Яндекс.Каталог</td>
                                <td>
                                    <?=$form->field($category, 'ymlExport', [
                                        //'inline'    =>  true,
                                        //'enableLabel'   =>  false
                                    ])->radioList([
                                        '1' =>  'Включена',
                                        '0' =>  'Выключена'
                                    ], [
                                        'id'    =>  'ymlExport',
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
                            <tr>
                                <td>Товары из этой категории</td>
                                <td>
                                    <?=$form->field($category, 'canBuy', [
                                        //'inline'    =>  true,
                                        //'enableLabel'   =>  false
                                    ])->radioList([
                                        '1' =>  'Продаются',
                                        '0' =>  'Не продаются'
                                    ], [
                                        'id'    =>  'canBuy',
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <center>
            <button class="btn btn-lg btn-success" style="margin: 0 auto" type="submit">Сохранить</button>
            или
            <a href="/goods/showcategory/<?=$category->ID?>" type="button" class="btn btn-info">В режим просмотра</a>
        </center>
    </div>
</div>