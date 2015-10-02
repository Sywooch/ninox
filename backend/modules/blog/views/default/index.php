<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\blog\controllers\LinkController;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticlesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить Статью', [LinkController::getForAdmin('create')], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider'  => $dataProvider,
        'filterModel'   => $searchModel,
        'summary'       => '',
        'columns'       => [

            'title:ntext',
            'link:ntext',
            'date',

            [
                'class'     => 'yii\grid\ActionColumn',
                'buttons'   => [
                    'update'    =>  function($url, $model){
                        return '<a href="'.LinkController::getForAdmin('update').$model->id.'"><span class="glyphicon glyphicon-pencil"></span></a> ';
                    },
                    'view'      =>  function($url, $model){
                        return '<a href="'.LinkController::getForAdmin('view').$model->id.'"><span class="glyphicon glyphicon-eye-open"></span></a> ';
                    }

                ],
                'template'  =>  '{view}{update}'
            ],
        ],
    ]); ?>

</div>
