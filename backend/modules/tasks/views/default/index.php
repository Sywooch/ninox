<?php
use app\models\Task;

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;

$smartfilter = \Yii::$app->request->get("smartfilter");

$css = <<<'STYLE'
    .userBlock{
        padding: 10px;
        height: 100%;
    }

    .userBlock div img{
        max-height: 50px;
        display: inline-block;
        margin-top: -20px;
    }

    .userBlock h2{
        height: 50px;
        vertical-align:middle;
        margin-top: 5px;
        display: inline-block;
        margin-left: -5px;
    }

    .userBlock .nowTask{
        vertical-align: bottom;
        display: block;
    }

    .user-clickable-block:hover{
        cursor: pointer;
    }
STYLE;
$this->registerCss($css);

$js = <<<'SCRIPT'

function setModalsAndBackdropsOrder() {
  var modalZIndex = 1040;
  $('.modal.in').each(function(index) {
    var $modal = $(this);
    modalZIndex++;
    $modal.css('zIndex', modalZIndex);
    $modal.next('.modal-backdrop.in').addClass('hidden').css('zIndex', modalZIndex - 1);
});
  $('.modal.in:visible:last').focus().next('.modal-backdrop.in').removeClass('hidden');
}

$(document)
  .on('show.bs.modal', '.modal', function(event) {
    $(this).appendTo($('body'));
  })
  .on('shown.bs.modal', '.modal.in', function(event) {
    setModalsAndBackdropsOrder();
  })
  .on('hidden.bs.modal', '.modal', function(event) {
    setModalsAndBackdropsOrder();
  });

changeTaskStatus = function(e){
    e.preventDefault();

    e = e.currentTarget;

    var btn = e.parentNode.parentNode.parentNode.querySelector(".btn-block");

    $.ajax({
        type: 'POST',
        url: '/admin/tasks/changetaskstatus',
        data: {
            'taskID': e.getAttribute("data-task"),
            'status': e.getAttribute("data-status")
        },
        success: function(data){
            switch(data){
                case '1':
                    btn.setAttribute("class", "btn btn-warning btn-block dropdown-toggle");
                    btn.innerHTML = "В работе <span class=\"caret\"></span>";
                    break;
                case '2':
                    btn.setAttribute("class", "btn btn-success btn-block dropdown-toggle");
                    btn.innerHTML = "Сделана <span class=\"caret\"></span>";
                    break;
                case '0':
                default:
                    btn.setAttribute("class", "btn btn-info btn-block dropdown-toggle")
                    btn.innerHTML = "Новая <span class=\"caret\"></span>";
                    break;
            }


        }
    });
}, showUserModal = function(e, id){
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: '/admin/tasks/getuserinfo',
        data: {
            'userID': id
        },
        success: function(data){
            document.querySelector("#user-modal .modal-body").innerHTML = data.content;
            $("#user-modal").modal().show();
        }
    });
}, showTaskModal = function(taskID){
    $.ajax({
        type: 'POST',
        url: '/admin/tasks/viewtask',
        data: {
            'taskID': taskID
        },
        success: function(data){
            if(data == false){
                location.reload();
            }
            document.querySelector("#task-modal .modal-header").innerHTML = '<h4 class="modal-title">' + data.title + '</h4>';
            document.querySelector("#task-modal .modal-body").innerHTML = data.content;
            $("#task-modal").modal().show();
        }
    });
}
SCRIPT;
$this->registerJs($js);

$slickItems = [];
$calendarEvents = [];

foreach($users as $user) {
    $nowWork = $user->workStatus >= 1 ? '<div class="nowTask">Текущая задача: asdf</div>' : '';

    $slickItems[] = '<div class="userBlock">
        <div class="user-clickable-block" onclick="showUserModal(event, \''.$user->id.'\')" data-userID="'.$user->id.'">
            <img src="'.$user->avatar.'" class="img-thumbnail img-circle">
            &nbsp;
            <h2>'.$user->name.' <small>'.\app\models\Siteuser::$workStatuses[$user->workStatus].'</small></h2>
        </div>'.$nowWork.'
    </div>';
}

foreach($events as $event){
    $d = new DateTime((($event->dateTo != '0000-00-00' && $event->dateTo < $event->desiredDateTo) ? $event->dateTo : $event->desiredDateTo));
    $d->add(new DateInterval("P1D"));
    $calendarEvents[] = [
        'title'     =>  $event->title,
        'color'     =>  Task::$priorityColors[$event->priority],
        'start'     =>  $event->dateFrom,
        'end'       =>  $d->format('Y-m-d'),
        'id'        =>  $event->id,
        'editable'  =>  false
    ];
    if($event->desiredDateTo < date("Y-m-d") && $event->desiredDateTo < date('Y-m-d') && ($event->dateTo == '0000-00-00' || $event->dateTo > $event->desiredDateTo)){
    //if($event->dateTo > $event->desiredDateTo && $event->dateTo < date('Y-m-d') && $event->dateTo != '0000-00-00'){
        $e = new DateTime($event->dateTo);
        $calendarEvents[] = [
            'title'     =>  'Просроченая задача: '.$event->title,
            'id'        =>  $event->id,
            'color'     =>  Task::$priorityColors[5],
            'start'     =>  $d->format('Y-m-d'),
            'end'       =>  $event->dateTo != '0000-00-00' ? $event->dateTo : date("Y-m-d"),
        ];
    }
}
?>
<h1>Задачи</h1>
<?=evgeniyrru\yii2slick\Slick::widget([
    'itemContainer' =>  'div',
    'items'         =>  $slickItems,
    'itemOptions'   =>  [
        'style' =>  'margin: 0 10px; height: 120px; background: #E4F1FE;',
    ],
    'clientOptions' => [
        'dots'     => false,
        'speed'    => 1000,
        'autoplay' => true,
        'arrows'    => false,
        'swipeToSlide'    => false,
        'infinite' => true,
        'slidesToShow' => 3,
        'slidesToScroll' => 1,
        'responsive' => [
            [
                'breakpoint' => 1200,
                'settings' => [
                    'slidesToShow' =>2,
                    'slidesToScroll' => 1,
                    'infinite' => true,
                    'autoplay' => true,
                ],
            ],
            [
                'breakpoint' => 992,
                'settings' => [
                    'slidesToShow' => 2,
                    'slidesToScroll' => 1,
                    'infinite' => true,
                    'autoplay' => true,
                ],
            ],
            [
                'breakpoint' => 768,
                'settings' => [
                    'slidesToShow' => 1,
                    'slidesToScroll' => 1,
                    'infinite' => true,
                    'autoplay' => true,
                ],
            ],

        ],
    ],
]);
\yii\widgets\Pjax::begin()?>
<div style="display: none;">
    <input id="dateFrom" type="text">
    <input id="dateTo" type="text">
</div>
<div>
    <div class="btn-group-lg" style="display: inline-block">
        <script>
            function someFunc(){
                var a   = new Date(document.querySelector('#dateFrom').value),
                    b   = new Date(document.querySelector('#dateTo').value);

                a = a.getFullYear() + '-' + a.getMonth() + '-' + a.getDate();
                b = b.getFullYear() + '-' + b.getMonth() + '-' + b.getDate();

                console.log(a + ' ' + b );
            }
        </script>
        <?=\app\components\AddTaskWidget::widget([
        ])?>
    </div>
    <a class="btn btn-default <?=$smartfilter == null ? 'active' : ''?>" href="/admin/tasks">Все</a>
    <a class="btn btn-info <?=$smartfilter == 'inWork' ? 'active' : ''?>" href="/admin/tasks?smartfilter=inWork">В работе</a>
    <a class="btn btn-danger <?=$smartfilter == 'stitched' ? 'active' : ''?>" href="/admin/tasks?smartfilter=stitched">Простроченые</a>
</div>
&nbsp;
<?=\talma\widgets\FullCalendar::widget([
    'config'   =>  [
        'selectable'    =>  true,
        'timezone'      =>  'Europe/Kiev',
        'weekends'      =>  true,
        'select'        =>  new \yii\web\JsExpression("function(a, b, c, d) {
            document.querySelector(\"#dateFrom\").value = a;
            document.querySelector(\"#dateTo\").value = b;
            end = b;
        }"),
        'click'        =>  new \yii\web\JsExpression("function() {
            console.log('asdf');
        }"),
        'eventClick'      =>  new \yii\web\JsExpression("function(calEvent, jsEvent, view){
            showTaskModal(calEvent.id);
        }"),
        'events'     =>  $calendarEvents,
        'selectHelper'  =>  true,
    ]
]);
\yii\widgets\Pjax::end()?>

<h1>Список задач</h1>
<?php \yii\widgets\Pjax::begin()?>
<?=\kartik\grid\GridView::widget([
    'dataProvider'      =>  $dataProvider,
    'summary'           =>  '',
    'striped'           =>  true,
    'bordered'          =>  false,
    'hover'             =>  true,
    'columns'           =>  [
        'title',
        'description',
        [
            'attribute' =>  'author',
            'value'     =>  function($model){
                $user = \app\models\Siteuser::getUser($model->author);
                return $user->name;
            }
        ],
        [
            'attribute' =>  'desiredDateTo',
            'value'     =>  function($model){
                return \Yii::$app->formatter->asDate($model->desiredDateTo, 'php:d.m.Y');
            }
        ],
        [
            'attribute' =>  'status',
            'value'     =>  function($model){
                return Task::$statuses[$model->status];
            }
        ]
    ]
])?>
<?php \yii\widgets\Pjax::end()?>
<h3>Выполненые</h3>
<?php \yii\widgets\Pjax::begin()?>
<?=\kartik\grid\GridView::widget([
    'dataProvider'      =>  $doneEvents,
    'summary'           =>  '',
    'striped'           =>  true,
    'bordered'          =>  false,
    'showHeader'        =>  false,
    'tableOptions'      =>  [
        'style'         =>  'color: #aaa'
    ],
    'hover'             =>  true,
    'columns'           =>  [
        'title',
        'description',
        [
            'attribute' =>  'author',
            'value'     =>  function($model){
                $user = \app\models\Siteuser::getUser($model->author);
                return $user->name;
            }
        ],
        [
            'attribute' =>  'dateTo',
            'value'     =>  function($model){
                return \Yii::$app->formatter->asDate($model->dateTo, 'php:d.m.Y');
            }
        ],
        [
            'attribute' =>  'status',
            'value'     =>  function($model){
                return Task::$statuses[$model->status];
            }
        ]
    ]
])?>
<?php \yii\widgets\Pjax::end()?>
<?php \yii\bootstrap\Modal::begin([
    'id'        =>  'task-modal',
    'size'      =>  \yii\bootstrap\Modal::SIZE_LARGE,
])?>

<?php \yii\bootstrap\Modal::end()?>
<?php \yii\bootstrap\Modal::begin([
    'id'        =>  'user-modal',
    'headerOptions' =>  [
        'style' =>  'display: none'
    ],
    'size'      =>  \yii\bootstrap\Modal::SIZE_DEFAULT,
])?>

<?php \yii\bootstrap\Modal::end()?>