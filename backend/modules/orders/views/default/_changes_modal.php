<button class="remodal-close" data-remodal-action="close"></button>
<?=\yii\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'columns'       =>  [
        [
            'attribute' =>  'model',
            'value'     =>  function($model){
                switch($model->model){
                    case 'backend\models\History':
                        return 'Заказ';
                        break;
                    case 'backend\models\SborkaItem':
                        return 'Товар';
                        break;
                    default:
                        return $model->model;
                }
            }
        ],
        [
            'format'    =>  'html',
            'attribute' =>  'field',
            'options'   =>  [
                'style' =>  'text-align: left'
            ],
            'value'     =>  function($model){
                $m = new $model->model;
                switch($model->action){
                    case 'CREATE':
                    case 'SET':
                        $p = '<i class="glyphicon glyphicon-plus" title="Добавилось"></i>';
                        break;
                    case 'CHANGE':
                        $p = '<i class="glyphicon glyphicon-pencil" title="Редактировалось"></i>';
                        break;
                    case 'DELETE':
                        $p = '<i class="glyphicon glyphicon-trash" title="Было удалено"></i>';
                        break;
                    default:
                        $p = $model->action;
                }
                return $p.' '.$m->getAttributeLabel($model->field);
            }
        ],
        [
            'attribute' =>  'old_value',
            'format'    =>  'html',
            'contentOptions'   =>  [
                'style' =>  'max-width: 180px;',
            ],
            'value'     =>  function($model){
                if(preg_match('/((-.*)|(.*\w+))(.(jpg|png|gif|jpeg))/', $model->old_value, $match)){
                    $model->old_value = preg_replace('/((-.*)|(.*\w+))(.(jpg|png|gif|jpeg))/', '<img class="img-thumbnail" style="max-width: 200px;" src="/img/catalog/'.$match['0'].'">', $model->old_value);
                }elseif(in_array($model->field, [
                    'show_img', 'deleted'
                ])){
                    switch($model->field){
                        default:
                            return $model->old_value == '1' ? 'Да' : 'Нет';
                            break;
                    }
                }
                return $model->old_value == '' ? '&nbsp;' : $model->old_value;
            }
        ],
        [
            'attribute' =>  'new_value',
            'format'    =>  'html',
            'contentOptions'   =>  [
                'style' =>  'max-width: 180px;',
            ],
            'value'     =>  function($model){
                if(preg_match('/((-.*)|(.*\w+))(.(jpg|png|gif|jpeg))/', $model->new_value, $match)){
                    $model->new_value = preg_replace('/((-.*)|(.*\w+))(.(jpg|png|gif|jpeg))/', '<img class="img-thumbnail" src="/img/catalog/'.$match['0'].'">', $model->new_value);
                }elseif(in_array($model->field, [
                    'show_img', 'deleted'
                ])){
                    switch($model->field){
                        default:
                            return $model->new_value == '1' ? 'Да' : 'Нет';
                            break;
                    }
                }
                return $model->new_value == '' ? '&nbsp;' : $model->new_value;
            }
        ],
        [
            'attribute' =>  'user_id',
            'format'    =>  'html',
            'value'     =>  function($model){
                return $model->user_id;
                return \common\models\Siteuser::getUser($model->user_id);
            }
        ],
        [
            'attribute' =>  'stamp',
            'value'     =>  function($model){
                return \Yii::$app->formatter->asDatetime($model->stamp, 'php:d.m.Y H:i:s');
            }
        ],
    ]
])?>