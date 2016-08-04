<?php
use backend\modules\goods\models\GoodVideoForm;
use yii\bootstrap\Html;
use yii\data\ArrayDataProvider;
use yii\widgets\ListView;

$css = <<<'CSS'
    .embed-responsive{
        margin-bottom: 15px;
    }
CSS;

$this->registerCss($css);

$model = new GoodVideoForm(['id' => $good->ID]);

echo ListView::widget([
        'dataProvider'  =>  new ArrayDataProvider([
            'models'    =>  $good->videos,
        ]),
        'itemOptions'   =>  [
            'class' =>  'embed-responsive embed-responsive-16by9',
        ],
        'options'       =>  [
            'class' =>  'video-list',
        ],
        'summary'       =>  '',
        'itemView'      =>  function($model){
            return Html::tag('iframe', '', [
                'class'             =>  'embed-responsive-item',
                'src'               =>  'https://www.youtube.com/embed/'.$model->video,
                'frameborder'       =>  0,
                'allowfullscreen'   =>  true,
            ]);
        },
    ]);
$form = \yii\widgets\ActiveForm::begin([
    'id'                    =>  'add-video',
    'action'                =>  '/goods/add-video',
    'enableAjaxValidation'  =>  true,
    'validationUrl'         =>  '/goods/validate-video-url',
]);
echo $form->field($model, 'id')->hiddenInput()->label(false).
    $form->field($model, 'url')->textInput().
    Html::submitButton('Загрузить');
$form->end();

$js = <<<'JS'
    $('#add-video').on('beforeSubmit', function(e) {
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            success: function(data){
                if(data){
                    var iframe = document.createElement('iframe');
                    var div = document.createElement('div');
                    div.appendChild(iframe);
                    div.setAttribute('class', 'embed-responsive embed-responsive-16by9');
                    iframe.setAttribute('src', 'https://www.youtube.com/embed/' + data);
                    iframe.setAttribute('class', 'embed-responsive-item')
                    iframe.setAttribute('frameborder', '0');
                    iframe.setAttribute('allowfullscreen', 'true');
                    $('.video-list').find('.empty').remove();
                    $('.video-list').append(div);
                    $('#goodvideoform-url').val('');
                }
            },
            error: function(){
                alert('Something went wrong');
            }
        });
    }).on('submit', function(e){
        e.preventDefault();
    });
JS;

$this->registerJs($js);

