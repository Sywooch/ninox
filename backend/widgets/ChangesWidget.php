<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.06.15
 * Time: 18:01
 */

namespace backend\widgets;

use backend\models\User;
use common\models\Siteuser;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use sammaye\audittrail\AuditTrail;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;

class ChangesWidget extends Widget{

    public $model;
    public $header = 'Изменения';
    public $dataProvider;
    public $changesButtonLabel = '<i class="glyphicon glyphicon-list-alt"></i> Список изменений';
    public $pageSize = '20';
    public $changesButtonClass = 'btn btn-default';
    private $siteusers = [];


    public function init(){
        if(empty($this->dataProvider)){
            $this->dataProvider = AuditTrail::find()->where(['model' => $this->model->className(), 'model_id' => $this->model->ID])->orderBy('ID DESC');
        }

        $siteusers = User::find()->select(['id', 'name', 'username'])->all();
        foreach($siteusers as $user){
            $this->siteusers[$user->id] = ['name' => $user->name, 'username' => $user->username];
        }
    }

    private function getUser($id){
        return array_key_exists($id, $this->siteusers) ? $this->siteusers[$id] : new Siteuser([
            'name'      =>  'system',
            'username'  =>  'system'
        ]);
    }

    public function run(){
        $js = <<<'SCRIPT'
    var revertChanges = function(obj){
        var itemID = obj.parentNode.parentNode.getAttribute('data-key');
        $.ajax({
            type: 'POST',
            url: '/revertchanges',
            data: {
                'itemid': itemID
            },
            success: function(data){
                console.log(data);
            }
        });
    }

    var revertChangesItems = document.querySelectorAll(".revertChanges");
    for(var i = 0; i < revertChangesItems.length; i++){
        revertChangesItems[i].addEventListener('click', function(e){
            e.preventDefault();
            revertChanges(e.currentTarget);
        }, false);
    }
SCRIPT;

        $this->getView()->registerJs($js);

        Modal::begin([
            'header' => $this->header,
            'options'   =>  [
                'style' =>  'color: black'
            ],
            'toggleButton' => [
                'label'     =>  $this->changesButtonLabel,
                'disabled'  =>  empty($this->dataProvider->all()),
                'class'     =>  $this->changesButtonClass
            ],
            'size'  =>  Modal::SIZE_LARGE,
        ]);
        echo GridView::widget([
            'pjax'          =>  true,
            'dataProvider'  =>  new ActiveDataProvider([
                'query' =>  $this->dataProvider,
                'pagination'    =>  [
                    'pageSize'  =>  $this->pageSize
                ]
            ]),
            'summary'  =>  'Показаны записи {begin} - {end}',
            'striped'   =>  true,
            'bordered'  =>  false,
            'columns'   =>  [
                [
                    'class'     =>  ActionColumn::className(),
                    'buttons'   =>  [
                        'revert'    =>  function($url, $model, $key){
                            return '<button class="revertChanges btn-link glyphicon glyphicon-share-alt"></button>';
                        }
                    ],
                    'template'  =>  '{revert}',
                    'header'    =>  '',
                    'width'     =>  '10px'
                ],
                [
                    'attribute' =>  'field',
                    'format'    =>  'html',
                    'value'     =>  function($model){
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
                        return $p.' '.$this->model->getAttributeLabel($model->field);
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
                            'enabled', 'show_img', 'deleted'
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
                            'enabled', 'show_img','deleted'
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
                    'value'     =>  function($model){
                        return $this->getUser($model->user_id);
                    }
                ],
                [
                    'attribute' =>  'stamp',
                    'value'     =>  function($model){
                        return \Yii::$app->formatter->asDatetime($model->stamp, 'php:d.m.Y H:i:s');
                    }
                ],
            ]
        ]);
        Modal::end();
    }
}