<?php
namespace common\panels;

use yii\base\Event;
use yii\base\View;
use yii\base\ViewEvent;
use yii\bootstrap\Html;
use yii\debug\Panel;


class ViewsPanel extends Panel
{
    private $_viewFiles = [];

    public function init()
    {
        parent::init();
        Event::on(View::className(), View::EVENT_BEFORE_RENDER, function (ViewEvent $event) {
            $this->_viewFiles[] = $event->sender->getViewFile();
        });
    }


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Views';
    }

    /**
     * @inheritdoc
     */
    public function getSummary()
    {
        $url = $this->getUrl();

        return Html::tag('div', Html::a('Views '.Html::tag('span', count($this->data), ['class' => 'yii-debug-toolbar__label']), $url), ['class' => 'yii-debug-toolbar__block']);
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        return '<ol><li>' . implode('<li>', $this->data) . '</ol>';
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->_viewFiles;
    }
}