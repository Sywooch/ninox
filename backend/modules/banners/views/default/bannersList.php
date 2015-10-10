<?php
use kartik\sortable\Sortable;

$this->title = $bannersCategory->description;

$this->params['breadcrumbs'][] = [
    'url'   =>  '/admin/banners',
    'label' =>  'Баннеры'
];
$this->params['breadcrumbs'][] = $this->title;

$items = [];

if(!empty($banners)){
    foreach($banners as $banner){
        $items[] = [
            'content'   =>  $this->render('_banner_item', [
                'model' =>  $banner
            ]),
            'options'   =>  [
                'data-banner-id' => $banner->id,
                'height'         => '200px',
                'class'          => 'list-group-item'.($banner->state != '1' ? ' list-group-item-warning' : '').($banner->deleted == 1 ? ' list-group-item-danger' : '')
            ]
        ];
    }
}


$js = <<<'SCRIPT'
var updateBannersOrder = function(e){
    var a = document.querySelectorAll(".banners-order li.list-group-item"),
        b = new Array(),
        c = document.querySelector(".banners-order").getAttribute('data-banners-category');

    for(var i = 0; i < a.length; i++){
        b.push(a[i].getAttribute("data-banner-id"));
    }

    $.ajax({
		type: 'POST',
		url: '/admin/banners',
		data: {
		    'data': b,
		    'action': 'updatebannerssort',
		    'category': c
		}
	});
}, changeBannerState = function(e){
    var row =   e.target.parentNode.parentNode.parentNode.parentNode,
        text=   e.target.parentNode.parentNode.querySelector("h3");

    $.ajax({
        type: 'POST',
        url: '/admin/banners',
        data: {
            'action': 'changebannerstate',
            'banner': row.getAttribute('data-banner-id'),
            'field':  'state'
        },
        success: function(data){
            if(data.state == 1){
                e.target.innerHTML = 'Выключить';
                if(data.deleted == 1){
                    text.innerHTML = 'Удалён';
                }else{
                    text.innerHTML = 'Активен с ' + data.dateStart + ' по ' + data.dateEnd;
                }
                row.setAttribute('class', row.getAttribute('class').replace(/list-group-item-warning/g, ''));
            }else{
                e.target.innerHTML = 'Включить';
                if(data.deleted == 1){
                    text.innerHTML = 'Удалён';
                }else{
                    text.innerHTML = 'Неактивен';
                }
                row.setAttribute('class', row.getAttribute('class') + ' list-group-item-warning');
            }
        }
    });
}, changeBannerDeletedState = function(e){
    var row =   e.target.parentNode.parentNode.parentNode.parentNode,
        text=   e.target.parentNode.parentNode.querySelector("h3");

    $.ajax({
        type: 'POST',
        url: '/admin/banners',
        data: {
            'action': 'changebannerstate',
            'banner': row.getAttribute('data-banner-id'),
            'field':  'deleted'
        },
        success: function(data){
            if(data.deleted != 1){
                e.target.innerHTML = 'Удалить';
                if(data.state == 1){
                    text.innerHTML = 'Активен с ' + data.dateStart + ' по ' + data.dateEnd;
                }else{
                    text.innerHTML = 'Неактивен';
                }
                row.setAttribute('class', row.getAttribute('class').replace(/list-group-item-danger/g, ''));
            }else{
                e.target.innerHTML = 'Восстановить';
                text.innerHTML = 'Удалён';
                row.setAttribute('class', row.getAttribute('class') + ' list-group-item-danger');
            }
        }
    });
}


var a = document.querySelectorAll('.changeBannerState');
for(var i = 0; i < a.length; i++){
    a[i].addEventListener('click', changeBannerState, false);
}


a = document.querySelectorAll('.deleteBanner');
for(var i = 0; i < a.length; i++){
    a[i].addEventListener('click', changeBannerDeletedState, false);
}
SCRIPT;

$this->registerJs($js);
?>
<h1><?=$this->title?> <small>Баннеры</small></h1>
<div class="btn-group">
    <?=\common\components\AddBannerWidget::widget([
        'defaultCategory'   =>  $bannersCategory->id
    ])?>
    <?=\common\components\AddBannerGroupWidget::widget([
        'model'         =>  $bannersCategory,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>&nbsp;Редактировать эту категорию'
    ])?>
</div>
    <br><br>
<?=Sortable::widget([
    'items' =>  $items,
    'options'   =>  [
        'class' =>  'list-group banners-order',
        'data-banners-category' =>  $bannersCategory->id
    ],
    'itemOptions'   =>  [
        'max-height'    =>  '200px',
    ],
    'pluginOptions' =>  [
        'forcePlaceholderSize'  =>  true
    ],
    'pluginEvents' => [
        'sortupdate' => 'function() { updateBannersOrder(); }',
    ]
]);?>