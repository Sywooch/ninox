<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.07.15
 * Time: 15:23
 */

namespace backend\widgets;

use common\models\Siteuser;
use kartik\form\ActiveForm;
use yii\base\Widget;
use yii\bootstrap\Modal;

class AddUserWidget extends Widget{

    public $buttonClass     =   'btn btn-default';
    public $buttonText      =   '<i class="glyphicon glyphicon-plus"></i>&nbsp;Добавить нового пользователя';
    public $modalHeader     =   '<h2>Добавить нового пользователя</h2>';
    public $query           =   null;
    public $defaultCategory;
    public $model;

    public function init(){
        if(empty($this->model)){
            $this->model = new Siteuser();
        }
    }

    public function run(){
        $form = new ActiveForm([
            'id' => 'login-form-horizontal',
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
        ]);
        Modal::begin([
            'header' => $this->modalHeader,
            'options'   =>  [
                'style' =>  'color: black'
            ],
            'toggleButton' => [
                'label'     =>  $this->buttonText,
                'class'     =>  $this->buttonClass
            ],
            'size'  =>  Modal::SIZE_DEFAULT,
        ]);
        $form->begin();
        echo    $form->field($this->model, 'username'),
                $form->field($this->model, 'name'),
                $form->field($this->model, 'password')->passwordInput(),
                $form->field($this->model, 'default_route'),
                $form->field($this->model, 'phone'),
                $form->field($this->model, 'birthdate'),
                $form->field($this->model, 'avatar'),
                $form->field($this->model, 'active')->checkbox(),
                $form->field($this->model, 'showInStat')->checkbox(),
                $form->field($this->model, 'tasksUser')->checkbox(),
                $form->field($this->model, 'tasksRole'),
                $form->field($this->model, 'id')->hiddenInput()->label(false),
                '<center><button class="btn btn-default">Сохранить</button></center>';
        $form->end();
        Modal::end();
    }

}