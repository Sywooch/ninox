<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/1/2015
 * Time: 1:38 PM
 */
namespace backend\widgets;
use backend\modules\blog\controllers\LinkController;
use backend\models\Question;
use kartik\form\ActiveForm;
use yii\base\Widget;
use yii\bootstrap\Modal;
use kartik\file\FileInput;

class AddQuestionWidget extends Widget{

    public $model;
    public $header;
    public $buttonLabel = '<i class="glyphicon glyphicon-plus"></i>';
    public $buttonClass = 'btn btn-default';
    public $modalSize = Modal::SIZE_DEFAULT;

    public function init(){
        if(empty($this->header)){
            $this->header = 'Редактировать';
        }
        if(empty($this->model)){
            $this->model = new Question();
        }
    }
    public function run(){
        Modal::begin([
            'header' => '<h2>'.$this->header.'</h2>',
            'options'   =>  [
                'style' =>  'color: black'
            ],
            'toggleButton' => [
                'label'     =>  $this->buttonLabel,
                'class'     =>  $this->buttonClass
            ],
            'size'  =>  $this->modalSize,
        ]);
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

        echo    $form->field($this->model, 'photo')->input('hidden') ,
                FileInput::widget([
                    'name'  =>  'Question[\'photo\']',
                    'options'=>[
                        'accept' => 'image/*',
                    ],
                    'pluginOptions' => [
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'showPreview' => true,
                        'uploadClass'   =>  'btn btn-info',
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '',
                        'browseLabel' =>  'Выбрать фото',
                        'uploadLabel' =>  'Загрузить',
                        'layoutTemplates'   =>  [
                            'main1' =>  '{preview}\n<div class="kv-upload-progress hide"></div>\n<div class="input-group {class}">\n{caption}\n
                                                         <div class="input-group-btn">\n{remove}\n{cancel}\n{browse}\n{upload}\n</div>\n</div>',
                            'main2' =>  '{preview} <div class="kv-upload-progress hide"></div><div class="row"><div class="col-xs-3">{browse}</div><div class="col-xs-4" style="margin-left: -17px;">{upload}</div></div>'
                        ],
                    ],
                ]);
        echo    $form->field($this->model, 'id')->hiddenInput()->label(false),
                $form->field($this->model, 'question')->textarea(),
                $form->field($this->model, 'answer')->textarea(),
                '</td></tr></table><button class="btn btn-lg btn-default center-block">Сохранить</button>';
        $form->end();
        Modal::end();
    }
}
?>

