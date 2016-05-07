<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\modules\blog\assets\BlogAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

BlogAsset::register($this);

$css = <<<'CSS'
.wrap{
margin-top: 100px;
}

.footer{
margin-top: 20px;
}
CSS;

$this->registerCss($css);

$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container">
        <?=\frontend\modules\blog\widgets\MenuWidget::widget([
            'items' =>  \common\models\BlogCategory::find()->with('childs')->where(['parent' => 0, 'taxonomy' => 'category'])->all()
        ]),
        Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">Krasota-Style BLOG</p>

        <p class="pull-right">Powered by lazy penguins</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
