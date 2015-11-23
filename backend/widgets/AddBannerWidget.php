<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.06.15
 * Time: 16:23
 */

namespace backend\widgets;

use common\models\Banner;
use common\models\BannerType;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use kartik\tabs\TabsX;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

class AddBannerWidget extends Widget{

    public $buttonClass     =   'btn btn-default';
    public $buttonText      =   '<i class="glyphicon glyphicon-plus"></i>&nbsp;Добавить новый баннер';
    public $modalHeader     =   '<h2>Добавить новый баннер</h2>';
    public $query           =   null;
    public $defaultCategory;
    public $model;

    public function init(){
        if(empty($this->model)){
            $this->model = new Banner();

            if(!empty($this->defaultCategory)){
                $this->model->bannerTypeId = $this->defaultCategory;
            }
        }
    }

    public function run(){
        $css = <<<'STYLE'

STYLE;

        $this->getView()->registerCss($css);

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

        $form = ActiveForm::begin();

        $items = [
            [
                'label'     =>  'HTML',
                'content'   =>  $form->field($this->model, 'banner').$form->field($this->model, 'link'),
                'active'    =>  $this->model->type == 'html'
            ],
            [
                'label'     =>  'Изображение',
                'active'    =>  $this->model->type == 'image' || $this->model->type == ''
            ]
        ];

        echo $form->field($this->model, 'type')->dropDownList([
            'image' =>  'Изображение',
            'html'  =>  'HTML'
        ]),
        $form->field($this->model, 'bannerTypeId')->widget(Select2::className(), [
            'data'  =>  BannerType::getList(),
            'value' =>  $this->defaultCategory,
            'options' => [
                'placeholder'   => 'Выберите категорию...',
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]),
        '<div class="form-inline">';
        echo '<div><b>Дата отображения</b></div>', $form->field($this->model, 'dateStart')->label(false)->widget(DatePicker::className(), [

        ]), $form->field($this->model, 'dateEnd')->label('&nbsp;-')->widget(DatePicker::className(), [

        ]);
        //$form->layout = '';
        echo '</div>',
        TabsX::widget([
            'items' =>  $items
        ]),
        $form->field($this->model, 'state')->widget(SwitchInput::classname(), [
            'type'  =>  SwitchInput::CHECKBOX,
            'pluginOptions' => [
                'onText' => 'Да',
                'offText' => 'Нет',
            ]
        ]),
        '<button class="btn btn-default btn-lg">Сохранить</button>';
        $form->end();
        Modal::end();
    }

}