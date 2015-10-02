<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.09.15
 * Time: 15:44
 */

namespace common\components;


use kartik\dropdown\DropdownX;
use rmrevin\yii\fontawesome\FA;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

class ServiceMenuWidget extends Widget{

    public $showDateButtons = true;
    public $showCurrencyBlock = true;
    public $canChangeCurrency = true;
    public $showUserDropdown = true;

    public $dateButtons = [
        [
            'label'     =>  'Сегодня',
            'link'  =>  '',
            'options'   =>  [
                'class' =>  'btn btn-default btn-sm',
            ]
        ],
        [
            'label'     =>  'Вчера',
            'link'  =>  'yesterday',
            'options'   =>  [
                'class' =>  'btn btn-default btn-sm',
            ]
        ],
        [
            'label'     =>  'Неделя',
            'link'  =>  'thisweek',
            'options'   =>  [
                'class' =>  'btn btn-default btn-sm',
            ]
        ],
        [
            'label'     =>  'Месяц',
            'link'  =>  'thismonth',
            'options'   =>  [
                'class' =>  'btn btn-default btn-sm',
            ]
        ],
    ];

    public $currencies = [];

    public $userDropdownItems = [
        ['label' => 'Action', 'url' => '#'],
        ['label' => 'Something else here', 'url' => '#'],
        '<li class="divider"></li>',
        ['label' => 'Separated link', 'url' => '#'],
    ];

    public $dateGetVariable = "showDates";




    public function init(){
        if($this->showDateButtons && empty($this->dateButtons)){
            throw new InvalidConfigException("Невозможно отобразить кнопки, когда их нет!");
        }

        if(empty($this->currencies)){
            $this->currencies = [
                [
                    'name'  =>  'usd',
                    'symbol'=>  '$',
                ],
                [
                    'name'  =>  'eur',
                    'symbol'=>  '€',
                ],
            ];
        }
    }

    private function findActive(){
        foreach($this->dateButtons as $key => $button){
            if(\Yii::$app->request->get($this->dateGetVariable) == $button['link']){
                $this->dateButtons[$key]['options']['disabled'] = 'disablbed';
            }
        }
    }

    private function renderDateButtons(){
        $buttonsHtml = '';

        $this->findActive();

        foreach($this->dateButtons as $button){
            $buttonsHtml .= Html::a($button['label'], RequestHelper::createGetLink($this->dateGetVariable, $button['link']), $button['options']);
        }

        return Html::tag('div', $buttonsHtml, [
            'class' =>  'btn-group'
        ]);
    }

    private function renderCurrencyBlock(){
        $data = 'Курсы валют: ЧР $8,5 €12,2&nbsp;&nbsp;|&nbsp;&nbsp;Сайт ';
        $currencies = [];

        foreach($this->currencies as $currency){
            $currencies[] = [
                'model' =>  \common\models\Service::findOne(['key' => $currency['name']]),
                'symbol'=>  $currency['symbol'],
                'tag'   =>  $currency['name']
            ];
        }

        foreach($currencies as $currency){
            $data .= $currency['symbol'];

            if($this->canChangeCurrency){
                $data .= \kartik\editable\Editable::widget([
                    'model'     =>  $currency['model'],
                    'attribute' =>  'value',
                    'options'   =>  [
                        'id'    =>  $currency['model']->key.'-site-value',
                    ],
                    'ajaxSettings'  =>  [
                        'type'      =>  'post',
                        'url'       =>  '/admin/updatecurrency',
                    ],
                    'afterInput'    =>  function ($form, $widget) {
                        echo '<input type="hidden" name="Service[key]" value="'.$widget->model->key.'" style="display: none">';
                    }
                ]);
            }else{
                $data .= $currency['model']->value;
            }
        }

        return Html::tag('span', $data);
    }

    private function renderLeftBlock(){
        $data = '';
        $date = strftime("%d %B %Y", time());

        if($this->showDateButtons){
            $data .= $this->renderDateButtons();
        }

        $data .= Html::tag('span', 'Сегодня: '.$date);

        return Html::tag('div', $data, [
            'class' =>  'items-left'
        ]);
    }

    private function renderCenterBlock(){
        $data = '';

        if($this->showCurrencyBlock){
            $data .= $this->renderCurrencyBlock();
        }

        return Html::tag('div', $data, [
            'class' =>  'items-center'
        ]);

    }

    protected function renderRightBlock(){
        $data = '';

        $data .= $this->renderUserBlock();

        return Html::tag('div', $data, [
            'class' =>  'items-right'
        ]);
    }

    public function renderUserBlock(){
        $data = 'Вы вошли как ';

        if($this->showUserDropdown){
            $data .= Html::beginTag('div', ['class'=>'dropdown', 'style'    =>  'display: inline']);
            $data .= Html::button(\Yii::$app->user->identity->name,
                ['type'=>'button', 'class'=>'btn btn-default btn-link', 'data-toggle'=>'dropdown']);
            $data .= DropdownX::widget([
                'items' => $this->userDropdownItems,
            ]);
            $data .= Html::endTag('div');
        }else{
            $data .= \Yii::$app->user->identity->name;
        }

        $data .= Html::a(FA::icon('power-off')->size(FA::SIZE_LARGE), '/admin/logout', [
            'data-method'   =>  'post'
        ]);

        return $data;
    }

    public function run(){
        return Html::tag('div', $this->renderLeftBlock().$this->renderCenterBlock().$this->renderRightBlock(), [
            'class' =>  'afterMenu'
        ]);
    }

}