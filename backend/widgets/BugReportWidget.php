<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.06.15
 * Time: 18:01
 */

namespace backend\widgets;

use common\models\Bug;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

class BugReportWidget extends Widget{

    public $model;
    public $header = 'Нашли баг?';
    public $dataProvider;
    public $buttonLabel = '';
    public $pageSize = '20';
    public $buttonClass = '';

    public function init(){

    }

    public function run(){
        Modal::begin([
            'toggleButton' => [
                'label'     =>  $this->buttonLabel,
                'class'     =>  $this->buttonClass,
                'tag'       =>  'div',
                'style'     =>  'display: none'
            ],
            'header'    =>  '<h4 class="modal-title" id="gridSystemModalLabel">'.$this->header.'</h4>',
            'options'       =>  [
                'id'        =>  'bugReportWidget',
            ]
        ]);
        echo 'Не беспокойтесь, скоро он будет исправлен! Достаточно всего-навсего заполнить форму ниже, чтобы мы знали о нём!', '<hr>';
        $form = new ActiveForm();
        $bug = new Bug();
        //print_r(\Yii::$app->g);
        $bug->userUrl = $bug->realUrl = \Yii::$app->request->url;

        echo $form->field($bug, 'name')->label('Название'),
            $form->field($bug, 'description')->textarea()->label('Описание'),
            $form->field($bug, 'userUrl')->label('<small>Если вы обнаружили баг не на этой странице, замените адрес в поле ниже на адрес страницы с ошибкой:</small>');
        Modal::end();
    }
}