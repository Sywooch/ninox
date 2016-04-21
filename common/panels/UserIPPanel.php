<?php
namespace common\panels;

use yii\base\Event;
use yii\base\View;
use yii\base\ViewEvent;
use yii\bootstrap\Html;
use yii\debug\Panel;


class UserIPPanel extends Panel
{

    public function init()
    {
        parent::init();
    }


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'UserIP';
    }

    public function getIP(){
        return \Yii::$app->request->userIP;
    }


    /**
     * @inheritdoc
     */
    public function getSummary()
    {
        return Html::tag('div', Html::a('your ip '.Html::tag('span', $this->getIP(), ['class' => 'yii-debug-toolbar__label'])), ['class' => 'yii-debug-toolbar__block']);
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        return 'Current user IP is '.$this->getIP();
    }

}