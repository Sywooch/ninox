<?php

use kartik\editable\Editable;

$css = <<<'STYLE'
body{
    margin-top: 70px;
}
.adminMenu{
    display: block;
    position: fixed;
    height: 50px;
    width: 100%;
    -webkit-box-shadow: 0px 10px 10px 0px rgba(50, 50, 50, 0.64);
    -moz-box-shadow:    0px 10px 10px 0px rgba(50, 50, 50, 0.64);
    box-shadow:         0px 10px 10px 0px rgba(50, 50, 50, 0.64);
    padding: 10px 0;
    margin-top: -70px;
    z-index: 1000000;
}
STYLE;

$this->registerCss($css);
?>

<div class="adminMenu">
    <nav class="navbar navbar-default" style="margin-top: -10px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Админменю</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" style="margin: 0 auto;">
                    <li>Текущий клиент: <?=Editable::widget([
                            'name'=>'person_name',
                            'asPopover' => true,
                            'header' => 'Name',
                            'format'    =>  'link',
                            'editableValueOptions'  =>  [
                            ],
                            'placement' =>  'bottom',
                            'size'=>'md',
                            'containerOptions' => [
                                'style' =>  'margin-top: 13px;'
                            ]
                        ])?></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>