<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 07.07.15
 * Time: 12:29
 */
namespace backend\widgets;
use common\models\ReviewType;
use kartik\form\ActiveForm;

use kartik\switchinput\SwitchInput;
use yii\base\Widget;
use yii\bootstrap\Modal;
class AddReviewGroupWidget extends Widget{

    public $model;
    public $header;
    public $buttonLabel = '<i class="glyphicon glyphicon-plus"></i>&nbsp;Добавить новую категорию';
    public $buttonClass = 'btn btn-default';
    public $modalSize = Modal::SIZE_DEFAULT;

    public function init(){

        if(empty($this->model)){
            $this->model = new ReviewType();
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
        $form = ActiveForm::begin([

        ]);

        echo    $form->field($this->model, 'id')->hiddenInput()->label(false),
                $form->field($this->model, 'review')->textarea( ),
                '</td></tr></table><button class="btn btn-lg btn-default center-block">Сохранить</button>';


                $form->end();
                Modal::end();

            }

    }