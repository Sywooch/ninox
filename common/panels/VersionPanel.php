<?php
namespace common\panels;

use yii\base\Event;
use yii\base\View;
use yii\base\ViewEvent;
use yii\debug\Panel;


class VersionPanel extends Panel
{

    public $currentVersion;
    public $commit;

    public function init()
    {
        parent::init();
    }


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Views';
    }

    public function getVersion(){
        if(!isset($this->currentVersion)){
            $this->currentVersion = exec('git describe --tags --always --long');
        }
        return $this->currentVersion;
    }

    public function getCommit(){
        if(!isset($this->commit)){
            $this->commit = exec('git show -s --format="%H"');
        }

        return $this->commit;
    }

    /**
     * @inheritdoc
     */
    public function getSummary()
    {
        return "<div class=\"yii-debug-toolbar-block\"><a href=\"".$this->getUrl()."\">Version <span class=\"label\">".$this->getVersion()."</span></a></div>";
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        return 'Version <a href="https://github.com/stylekrasota/krasota-style.yii/commit/'.$this->getCommit().'">'.$this->getVersion().'</a>';
    }

}