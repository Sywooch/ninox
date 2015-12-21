<?php
use kartik\grid\GridView;

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<'STYLE'
.fucking-header *{
    text-align: center;
}
STYLE;

$this->registerCss($css);

?>
<h1>Клиенты</h1>
<?=GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'filterModel'   =>  $searchModel,
    'tableOptions'   =>  [
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
    'columns'   =>  [
        [
            'header'    =>  '',
            'class'     =>  \yii\grid\ActionColumn::className(),
            'template'  =>  '<div class="center-block" style="display: block; width: 50px;">{view}&nbsp;{update}&nbsp;{delete}</div><div style="display: block">{labels}</div>',
            'buttons'   =>  [
                'update'    =>  function($url, $model, $key){
                    return '<a href="/customers/showcustomer/'.$model->ID.'?act=edit" title="Редактировать" aria-label="Редактировать" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>';
                },
                'delete'    =>  function($url, $model, $key){
                    return '<a href="#" title="Удалить" aria-label="Удалить" data-pjax="0" class="customer-remove" data-customer-id="'.$model->ID.'"><span class="glyphicon glyphicon-trash"></span></a>';
                },
                'view'    =>  function($url, $model, $key){
                    return '<a href="/customers/showcustomer/'.$model->ID.'" title="Просмотреть" aria-label="Просмотреть" data-pjax="0""><span class="glyphicon glyphicon-eye-open"></span></a>';
                },
                'labels'    =>  function($url, $model, $key){
                    $return = '';
                    if($model->black == 1){
                        $return .= '<span class="label label-danger">в чёрном списке</span>&nbsp;';
                    }
                    if($model->Deleted != 0){
                        $return .= '<span class="label label-info">удалён</span>&nbsp;';
                    }
                    return '<center>'.$return.'</center>';
                }
            ]
        ],
        [
            'attribute' =>  'ID',
            'label'     =>  'ID'
        ],
        [
            'attribute' =>  'Company',
            'label'     =>  'Ф.И.О.'
        ],
        [
            'attribute' =>  'Phone',
            'label'     =>  'Телефон'
        ],
        [
            'attribute' =>  'City',
            'label'     =>  'Город\область',
            'format'    =>  'html',
            'value'     =>  function($model){
                $model->City = preg_replace('/пгт,/', 'пгт.', $model->City);
                $m = explode(',', $model->City);
                $mm = sizeof($m);
                if($mm >= 2){
                    return "<span style=\"display: block; font-weight: bold;\">".$m[0]."</span><br>".$m[1]."";
                }elseif($mm == 1){
                    return "<span style=\"display: block; font-weight: bold;\">".$m[0]."</span>";
                }else{
                    return '-';
                }
            }
        ],
        [
            'attribute' =>  'CardNumber',
            'label'     =>  'Номер карты',
            'class'     =>  \kartik\grid\EditableColumn::className(),
            'editableOptions'   =>  [
                'valueIfNull'   =>  '(нет)',
                'header'    =>  ' номера карты',
                'size'      =>  'sm',
                'preHeader' =>  'Редактирование',
                'submitButton'   =>  [
                    'label' =>  'Сохранить',
                    'class' =>  'btn btn-success btn-sm'
                ],
                'resetButton'   =>  [
                    'label' =>  'Очистить',
                    'class' =>  'btn btn-info btn-sm'
                ],
            ],
        ],
        [
            'attribute' =>  'eMail',
            'label'     =>  'Электронная почта'
        ],
        [
            'attribute' =>  'UserRealTime',
            'label'     =>  'Дата добавления'
        ],
        [
            'attribute' =>  'money',
            'label'     =>  'Счёт',
            'format'    =>  'html',
            'class'     =>  \kartik\grid\EditableColumn::className(),
            'editableOptions'=> function ($model, $key, $index) {
                return [
                    'header'    =>  ' счёта',
                    'size'      =>  'sm',
                    'valueIfNull'   =>  '0',
                    'preHeader' =>  'Редактирование',
                    'submitButton'   =>  [
                        'label' =>  'Сохранить',
                        'class' =>  'btn btn-success btn-sm'
                    ],
                    'resetButton'   =>  [
                        'label' =>  'Очистить',
                        'class' =>  'btn btn-info btn-sm'
                    ],
                ];
            }
        ],
    ],
    'resizableColumns'  =>  true,
    'persistResize'     =>  true
])?>