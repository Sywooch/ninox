<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/3/2015
 * Time: 12:17 PM
 */
namespace backend\widgets;
use backend\models\Vote;
use kartik\form\ActiveForm;
use yii\base\Widget;
use yii\bootstrap\Modal;

class AddVoteWidget extends Widget{

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
            $this->model = new Vote();
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
        echo    $form->field($this->model, 'id')->hiddenInput()->label(false),
        $form->field($this->model, 'text')->textarea(),
        '</td></tr></table><button class="btn btn-lg btn-default center-block">Сохранить</button>';
        $form->end();
        Modal::end();
    }
}
?>

