<?php
$this->title = 'Пользователь "'.$user->username.'"';

$this->params['breadcrumbs'][] = [
    'url'   =>  '/users',
    'label' =>  'Пользователи'
];

$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?=$user->username?> <small>Пользователи</small></h1>
