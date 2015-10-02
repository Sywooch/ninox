<?php
use kartik\grid\GridView;

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<'STYLE'
.fucking-header *
{
    text-align: center;
}
STYLE;
$this->registerCss($css);

$js = <<<'SCRIPT'
    $(".btnpublished").click(
        function(e){
            $.ajax({
                type: 'POST',
                url: '/admin/feedback/pubordel',
                data: {
                    'colID': $(this).closest("tr").attr("data-key"),
                    'PubOrDel': 'published'
                },
                success: function(data){
                    e.currentTarget.innerHTML = data == 1 ? 'скрыть' : 'показать';
                }
            });
        });
    $(".btndelete").click(
        function(){
        $.ajax({
            type: 'POST',
            url: '/admin/feedback/pubordel',
            data: {
                'colID': $(this).closest("tr").attr("data-key"),
                'PubOrDel': 'deleted'
            },
            success: function(data){
                location.reload();
            }
        });
    });
SCRIPT;
$this->registerJs($js);
?>
    <h1>Отзывы</h1>
<?=GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'tableOptions'  =>  [
        'class'     =>  'table table-stripped table-hover block-center'
    ],
    'rowOptions'   =>  [
        'style'     =>  'text-align: center'
    ],
    'headerRowOptions'   =>  [
        'class'     =>  'fucking-header'
    ],
    'responsive'    =>  true,
    'summary'   =>  '<div>Показаны записи {begin} - {end} из {totalCount}</div>',
    'columns'   => [
        [
            'attribute' =>  'date',
            'label'     =>  'Дата',
            'value'     =>  function($model){
                return date( "Y-m-d \n H:i", strtotime($model->date) );
            }
        ],
        [
            'attribute' =>  'name',
            'label'     =>  'Имя'
        ],


        [
            'attribute' =>  'city',
        ],
        [
            'attribute' =>  'review',
            'label'     =>  'Отзыв'
        ],
        [
            'attribute' =>  'customerType',
            'label'     =>  'Тип покупателя'
        ],
        [
            'attribute' =>  'question1',
            'label'     =>  'Ответы на вопросы',
            'value'     =>  function($model)
            {
                return
                    'Ответ на 1 вопрос: '.$model->question1."\n".
                    'Ответ на 2 вопрос: '.$model->question2;
            }
        ],
        [
            'class' =>  \kartik\grid\ActionColumn::className(),
            'buttons'   =>
             [
                'hidden'  =>  function($url, $model)
                {
                    return '<button class="btn btn-default btnpublished">'.
                        ($model->published == 1 ? 'скрыть' : 'показать').'</button>';
                },
                'delete'  =>  function($url, $model)
                {
                    return '<button class="btn btn-default btndelete">удалить</button>';
                },
                'edit'    =>  function($url, $model)
                {
                    return 'редактировать';
                }
            ],
            'template'    =>  '{hidden}<br />{edit}<br />{delete}'
        ]
    ],
    'resizableColumns'  =>  true,
    'persistResize'     =>  true
])
?>