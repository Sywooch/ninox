<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 30.07.15
 * Time: 15:11
 */

namespace common\components;


use common\models\Task;
use kartik\daterange\DateRangePicker;
use kartik\form\ActiveForm;
use yii\base\Widget;
use yii\bootstrap\Modal;

class AddTaskWidget extends Widget{

    public $buttonClass =   'btn btn-default';
    public $buttonText  =   '<i class="glyphicon glyphicon-plus"></i>&nbsp;Добавить задачу';
    public $modalHeader =   '<h2>Добавить задачу</h2>';
    public $model;

    public function init(){
        if(empty($this->model)){
            $this->model = new Task();
        }
    }

    public function run(){
        Modal::begin([
            'header' => $this->modalHeader,
            'options'   =>  [
                'style' =>  'color: black'
            ],
            'clientEvents'  =>  [
                'shown.bs.modal'    =>  'function(){
                    if(document.querySelector("input#dateFrom").value != "" || document.querySelector("input#dateTo").value != ""){
                        var dateFrom, dateTo, date = new Date();

                        if(document.querySelector("input#dateFrom").value != ""){
                            var a, b;

                            dateFrom = document.querySelector("input#dateFrom").value;
                            dateFrom = new Date(dateFrom);

                            a = (dateFrom.getMonth() < 10 ? "0" + "" : "") + dateFrom.getMonth();
                            b = (dateFrom.getDate() < 10 ? "0" + "" : "") + dateFrom.getDate();

                            a++;

                            dateFrom = dateFrom.getFullYear() + "-" + a + "-" + b;
                        }else{
                            var a, b;

                            a = (date.getMonth() < 10 ? "0" + "" : "") + date.getMonth();
                            b = (date.getDate() < 10 ? "0" + "" : "") + date.getDate();

                            a++;

                            dateFrom = date.getFullYear() + "-" + a + "-" + b;
                        }

                        if(document.querySelector("input#dateTo").value != ""){
                            var a, b;

                            dateTo = document.querySelector("input#dateTo").value;
                            dateTo = new Date(dateTo);

                            a = (dateTo.getMonth() < 10 ? "0" + "" : "") + dateTo.getMonth();
                            b = (dateTo.getDate() < 10 ? "0" + "" : "") + dateTo.getDate();

                            a++;
                            b--;

                            dateTo = dateTo.getFullYear() + "-" + a + "-" + b;
                        }else{
                            var a, b;

                            a = (date.getMonth() < 10 ? "0" + "" : "") + date.getMonth();
                            b = (date.getDate() < 10 ? "0" + "" : "") + date.getDate();

                            a++;
                            b--;

                            dateTo = date.getFullYear() + "-" + a + "-" + b;
                        }
                    }


                    if(dateFrom != ""){
                        $("#datepicker-range-'.($this->model->id == '' ? 'new' : $this->model->id).'").data(\'daterangepicker\').setStartDate(dateFrom);
                    }

                    if(dateTo != ""){
                        $("#datepicker-range-'.($this->model->id == '' ? 'new' : $this->model->id).'").data(\'daterangepicker\').setEndDate(dateTo);
                    }
                }'
            ],
            'toggleButton' => [
                'label'     =>  $this->buttonText,
                'class'     =>  $this->buttonClass
            ],
            'size'  =>  Modal::SIZE_DEFAULT,
        ]);
        $form = new ActiveForm([
            'id' => 'login-form-horizontal',
            'type' => ActiveForm::TYPE_HORIZONTAL,
            'formConfig' => ['labelSpan' => 4, 'deviceSize' => ActiveForm::SIZE_SMALL]
        ]);
        $form->begin();
        echo
        $form->field($this->model, 'title'),
        $form->field($this->model, 'description')->textarea(),
        $form->field($this->model, 'dateRange', [
            'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
            'options'=>['class'=>'drp-container form-group']
        ])->widget(DateRangePicker::classname(), [
            'useWithAddon'  =>false,
            'convertFormat' =>true,
            'pluginOptions' =>[
                'format'=>'Y-m-d',
                'separator'=>' до ',
            ],
            'options'   =>  [
                'id'    =>   'datepicker-range-'.($this->model->id == '' ? 'new' : $this->model->id)
            ]
        ]),
        /*$form->field($this->model, 'dateFrom')->widget(DatePicker::className(), [
            'options'   =>  [
                'id'    =>  'datepicker-dateFrom-'.($this->model->id == '' ? 'new' : $this->model->id)
            ],
            'pluginOptions' =>  [
                'startDate' =>  date('Y-m-d'),
                'autoclose' =>  true
            ],
            'pluginEvents'  =>  [
                'changeDate'    =>  'function(e){
                    console.log(e.date);

                    $("#datepicker-dateTo-'.($this->model->id == '' ? 'new' : $this->model->id).'").datepicker({
                        \'startDate\': \'2015-07-30\'
                    });
                 }'
            ]
        ]),
        $form->field($this->model, 'desiredDateTo')->widget(DatePicker::className(), [
            'pluginOptions' =>  [
                'startDate' =>  date('Y-m-d'),
                'autoclose' =>  true
            ],
            'options'   =>  [
                'id'    =>  'datepicker-dateTo-'.($this->model->id == '' ? 'new' : $this->model->id)
            ],
        ]),*/
        $form->field($this->model, 'priority')->dropDownList(Task::$priorities),
        $form->field($this->model, 'id')->hiddenInput()->label(false),
            '<center><button class="btn btn-default">Сохранить</button></center>';
        $form->end();
        Modal::end();
    }



}