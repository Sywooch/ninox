<?php

use yii\helpers\Html;
use app\modules\blog\controllers\LinkController;

/* @var $this yii\web\View */
/* @var $model app\models\Articles */

$this->title = 'Добавление статьи';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => [LinkController::getForAdmin()]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
