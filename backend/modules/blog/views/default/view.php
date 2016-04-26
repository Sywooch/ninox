<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\blog\controllers\LinkController;

/* @var $this yii\web\View */
/* @var $model common\models\Articles */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => [LinkController::getForAdmin()]];
$this->params['breadcrumbs'][] = $this->title;

$css = <<<'STYLE'
.max-height-200{
    max-height: 200px;
}
STYLE;

$this->registerCss($css);

?>
<div class="articles-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', [LinkController::getForAdmin('update').$model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(($model->show ? 'Не отображать' : 'Отображать'), [LinkController::getForAdmin('display').$model->id], [
            'class' => ($model->show ? 'btn btn-danger' : 'btn btn-primary'),
            'data' => [
                'confirm' => 'Вы уверены, что '.($model->show ? 'не ' : '').'хотите отображать эту статью?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'ico',
                'value' =>  '<img class="img-thumbnail max-height-200" src="'.LinkController::getForImg().$model->photo.'">',
                'format'    =>  'html'
            ],
            'title',
            'date',
            'mod',
            'future_publish',
            'link',
            'show',
            'rate',
            'views',
        ],
    ]) ?>

</div>
