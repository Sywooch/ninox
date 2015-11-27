<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 07.07.15
 * Time: 12:29
 */

namespace backend\widgets;


use common\models\BannerType;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use yii\base\Widget;
use yii\bootstrap\Modal;

class AddBannerGroupWidget extends Widget{

    public $model;
    public $header;
    public $buttonLabel = '<i class="glyphicon glyphicon-plus"></i>&nbsp;Добавить новую категорию';
    public $buttonClass = 'btn btn-default';
    public $modalSize = Modal::SIZE_DEFAULT;

    public function init(){
        if(empty($this->header)){
            if(empty($this->model)){
                $this->header = 'Добавить новую категорию баннеров';
            }else{
                $this->header = 'Редактировать категорию баннеров';
            }
        }

        if(empty($this->model)){
            $this->model = new BannerType();
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
                $form->field($this->model, 'description'),
                $form->field($this->model, 'alias'),
                '<hr><h3>Опции:</h3><table style="width: 100%" border="0px"><tr><td style="width: 50%">',
                $form->field($this->model, 'sort')->widget(SwitchInput::classname(), [
                    'type'  =>  SwitchInput::CHECKBOX,
                    'pluginOptions' => [
                        'onText' => 'Да',
                        'offText' => 'Нет',
                    ],
                ]),
                '</td><td style="width: 50%">',
                $form->field($this->model, 'type')->widget(SwitchInput::classname(), [
                    'type'  =>  SwitchInput::CHECKBOX,
                    'pluginOptions' => [
                        'onText' => 'Да',
                        'offText' => 'Нет',
                    ]
                ]),

               '</td></tr><tr><td>',
                $form->field($this->model, 'bg')->widget(SwitchInput::classname(), [
                    'type'  =>  SwitchInput::CHECKBOX,
                    'pluginOptions' => [
                        'onText' => 'Да',
                        'offText' => 'Нет',
                    ]
                ]),  '</td><td>',
                $form->field($this->model, 'category')->widget(SwitchInput::classname(), [
                    'type'  =>  SwitchInput::CHECKBOX,
                    'pluginOptions' => [
                        'onText' => 'Да',
                        'offText' => 'Нет',
                    ]
                ]),
                '</td></tr></table><button class="btn btn-lg btn-default center-block">Сохранить</button>';


        $form->end();
        Modal::end();

    }


    }

