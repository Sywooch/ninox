<?php

use yii\helpers\Html;
use backend\modules\blog\controllers\LinkController;

/* @var $this yii\web\View */
/* @var $model common\models\Articles */

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
