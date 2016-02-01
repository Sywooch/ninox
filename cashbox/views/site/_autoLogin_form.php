<?php
use yii\bootstrap\Html;

$css = <<<'CSS'
.autologin-user{
    width: 100px;
    padding-top: 10px;
    -webkit-transition: all 0.5s ease-out;
    -moz-transition: all 0.5s ease-out;
    -o-transition: all 0.5s ease-out;
    transition: all 0.5s ease-out;
    opacity: 0.4;
    -webkit-filter: grayscale(100%);
    -moz-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    -o-filter: grayscale(100%);
    filter: grayscale(100%);
    filter: gray; /* IE 6-9 */
    float: left;
    margin-right: 10px;
}

.autologin-user:hover{
    opacity: 1;
    cursor: pointer;
    -webkit-filter: grayscale(0%);
    -moz-filter: grayscale(0%);
    -ms-filter: grayscale(0%);
    -o-filter: grayscale(0%);
    filter: grayscale(0%);
    filter: none; /* IE 6-9 */
}

.autologin-user div.image{
    width: 100px;
    height: 100px;
    overflow: hidden;
}

.autologin-user div.image img{
    max-height: 200px;
    min-height: 140px;
    margin: -25%
}

.autologin-user span{
    font-size: 16px;
    color: rgba(255, 255, 255, 0.7);
    text-shadow: 1px 1px 3px #23203b;
    text-align: center;
    display: block;
}


.nav-tabs{
    border-bottom: none;
}

.nav-tabs > li > a{
    color: #fff;
    border-radius: 4px;
}


.nav-tabs > li > a:hover{
    color: rgba(0, 0, 0, 0.6);
}
CSS;

$js = <<<'JS'
var login = function(userID){
    $.ajax({
        url:    '/login',
        data: {
            LoginForm: {
                userID: userID
            }
        },
        type:   'post',
        success: function(){
            location.href = '/';
        }
    });
};

$(".autologin-user").on('click', function(e){
console.log(e);
    login(e.currentTarget.getAttribute('data-userID'));
})
JS;


$this->registerCss($css);

$this->registerJs($js);

foreach($users as $user){
    echo Html::tag('div',
        Html::tag('div',
            Html::img(empty($user->avatar) ? '/img/krasota.png' : $user->avatar),
            [
                'class' => 'img-circle image'
            ]).
        Html::tag('span', $user->name),
        [
            'class' =>  'autologin-user',
            'data-userID' =>  $user->id
        ]);
}
?>