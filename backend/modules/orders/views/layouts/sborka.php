<?php

use yii\bootstrap\Html;

$css = <<<'CSS'

.sborka{
    background: #eae9e9;
/*    width: 700px;
    margin-bottom: 50px;
    padding-bottom: 10px;*/
    padding-bottom: 10px;
    height: auto;
    min-height: 100%;
}

.sborka .header{
    padding: 25px 35px;
    overflow: auto;
    width: 700px;
    margin: auto;
}

.sborka .header a:hover{
    text-decoration: none;
}

.sborka .header span{
    font-size: 36px;
    font-weight: bold;
    color: #464646;
}

.sborka .button{
    border: none;
    color: white;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    line-height: 45px;
}

.sborka .green-button{
    background: #95cc3e;
    border-bottom: 3px solid #628d1d;
}

.sborka .yellow-button{
    background: #ffc600;
    border-bottom: 3px solid #daa901;
    color: #503f05;
}

.sborka .red-button{
    background: #dd3939;
    border-bottom: 3px solid #9b3434;
}

.sborka .grey-button{
    background: #cbcbcb;
    border-bottom: 3px solid #a4a4a4;
}

.sborka .small-button{
    width: 148px;
    height: 48px;
}

.sborka .medium-button{
    height: 114px;
    width: 50%;
    float: left;
}

.sborka .header .yellow-button{
    float: left;
}

.sborka .header .green-button{
    float: right;
}

.sborka .order-number{
    width: 334px;
    float: left;
    text-align: center;
}

.sborka .typical-block{
    height: 260px;
    width: 685px;
    background: white;
    display: block;
    box-shadow: 0px 4px 5px #888888;
    margin: auto auto 10px
}

.sborka .typical-block .image{
    width: 50%;
    height: 100%;
    float: left;
}

.sborka .typical-block .content{
    float: left;
    width: 50%;
}

.sborka .items-count{
    width: 100%;
    height: 146px;
    padding: 50px;
}

.sborka .items-count input{
    width: 64px;
    height: 43px;
    font-size: 36px;
    font-weight: bold;
    text-align: center;
    border: 2px solid #cecece;
    color: #464646;
    vertical-align: middle;
}

.sborka .items-count a{
    font-size: 18px;
    font-weight: bold;
    text-decoration: underline;
    color: #464646;
    margin-left: 15px;
}

.sborka .items-count span{
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #464646;

}

.sborka .items-count .count{
    float: right;
    height: 42px;
    overflow: hidden;
    line-height: initial;
}

.sborka .image img{
    height: 100%;
    width: 100%;
}

.sborka .access img{
opacity: 0.2;
}

.sborka .denied img{
opacity: 0.2;
}

.sborka .typical-block .access .ico{
    background: url("/img/access.png") no-repeat;
    position: relative;
    width: 113px;
    height: 113px;
    margin: auto;
    display: block;
    margin-top: -180px;
}

.sborka .typical-block .denied .ico{
    background: url("/img/denied.png") no-repeat;
    position: relative;
    width: 113px;
    height: 113px;
    margin: auto;
    display: block;
    margin-top: -180px;
}


CSS;

$this->registerCss($css);

\backend\assets\AppAsset::register($this);

rmrevin\yii\fontawesome\AssetBundle::register($this);

$this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="https://<?=$_SERVER['SERVER_NAME']?>/favicon.ico">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?= $content ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>