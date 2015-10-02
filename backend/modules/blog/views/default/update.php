<?php

use yii\helpers\Html;
use app\modules\blog\controllers\LinkController;

/* @var $this yii\web\View */
/* @var $model app\models\Articles */

$this->title = 'Редактирование статьи';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => [LinkController::getForAdmin()]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => [LinkController::getForAdmin('view').$model->id]];
$this->params['breadcrumbs'][] = 'Редактирование статьи';
?>
<div class="articles-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
