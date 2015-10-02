<?php
use kartik\select2\Select2;

$keywords = $skeyword = [];
$t1 = "<div class=\"row\"><div class=\"col-lg-12\"><div class=\"row\"><div class=\"col-xs-2\" style=\"line-height: 34px; vertical-align: middle;\">{label}</div><div class=\"col-xs-10\">{input}</div></div><div class=\"col-lg-3\">{error}</div></div></div>";
$t2 = "<div class=\"row\"><div class=\"col-lg-12\">{input}</div><div class=\"col-lg-3\">{error}</div></div>";

$categoryUk = $category;

if(isset($category->keyword) && strlen($category->keyword) >= 1){
    $a = explode(', ', strip_tags($category->keyword));
    if(sizeof($a) >= 1 && $a['0'] != ""){
        foreach($a as $aa){
            $keywords[] = $aa;
            $skeyword[$aa] = $aa;
        }
    }
}
?>
<table class="table table-responsive good-table">
    <tbody>
    <tr>
        <td>Название</td>
        <td>
            <div style="margin-left: -20px">
                <?=$form->field($category, 'Name', [
                    'options'   =>  [
                        'class' =>  'col-xs-12'
                    ]
                ])?>
            </div>
        </td>
    </tr>
    <tr>
        <td><abbr title="Title категории">Тег "title"</abbr></td>
        <td>
            <div style="margin-left: -20px">
                <?=$form->field($category, 'title', [
                    'options'   =>  [
                        'class' =>  'col-xs-12'
                    ]
                ])?>
            </div>
        </td>
    </tr>
    <?php $form->fieldConfig['template']  =   $t1;?>
    <tr>
        <td><abbr title="SEO для title">SEO для тега "title"</abbr></td>
        <td>
            <div style="margin-left: -30px;">
                <?=$form->field($category, 'titleasc', [
                    'options'   =>  [
                        'class' =>  'col-xs-12'
                    ]
                ])->label("Дёшево")?>
                <?=$form->field($category, 'titledesc', [
                    'options'   =>  [
                        'class' =>  'col-xs-12'
                    ]
                ])->label("Дорого")?>
                <?=$form->field($category, 'titlenew', [
                    'options'   =>  [
                        'class' =>  'col-xs-12'
                    ]
                ])->label("Новинки")?>
            </div>
        </td>
    </tr>
    <?php $form->fieldConfig['template']  =   $t2; ?>
    <tr>
        <td><abbr title="H1 категории">Тег "H1"</abbr></td>
        <td>
            <div style="margin-left: -20px">
                <?=$form->field($category, 'h1', [
                    'options'   =>  [
                        'class' =>  'col-xs-12'
                    ]
                ])?>
            </div>
        </td>
    </tr>
    <?php $form->fieldConfig['template']  =   $t1; ?>
    <tr>
        <td><abbr title="SEO для H1">SEO для тега "H1"</abbr></td>
        <td>
            <div style="margin-left: -30px;">
                <?=$form->field($category, 'h1asc', [
                    'options'   =>  [
                        'class' =>  'col-xs-12'
                    ]
                ])->label("Дёшево")?>
                <?=$form->field($category, 'h1desc', [
                    'options'   =>  [
                        'class' =>  'col-xs-12'
                    ]
                ])->label("Дорого")?>
                <?=$form->field($category, 'h1new', [
                    'options'   =>  [
                        'class' =>  'col-xs-12'
                    ]
                ])->label("Новинки")?>
            </div>
        </td>
    </tr>
    <?php $form->fieldConfig['template']  =   $t2;?>
    <tr>
        <td>Описание</td>
        <td>
            <div>
                <?=yii\imperavi\Widget::widget([
                    'model' => $category,
                    'attribute' => 'descr',
                    'options' => [
                        'toolbar' => true,
                    ],
                ]);?>
            </div>
        </td>
    </tr>
    <tr>
        <td>Текст</td>
        <td>
            <div>
                <?=yii\imperavi\Widget::widget([
                    'model' => $category,
                    'attribute' => 'text2',
                    'options' => [
                        'toolbar' => true,
                    ],
                ]);?>
            </div>
        </td>
    </tr>
    <tr>
        <td>Ключевые слова</td>
        <td>
            <?=Select2::widget([
                'name'  =>  $category->formName().'[keywords]',
                'language'  =>  'ru',
                'value' => $keywords, // initial value
                'data' => $skeyword,
                'options' => ['placeholder' => 'Введите ключевые слова'],
                'pluginOptions' => [
                    'tags'  =>  true,
                    'tokenSeparators' => [',']
                ],
            ]);?>
        </td>
    </tr>
    </tbody>
</table>
<?php
$form->fieldConfig['template']  =   $t2;
?>