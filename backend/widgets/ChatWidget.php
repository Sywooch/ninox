<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.11.15
 * Time: 15:17
 */

namespace backend\widgets;


use backend\assets\ChatAsset;
use yii\base\Widget;

class ChatWidget extends Widget{

    public function run(){
        $this->getView()->registerAssetBundle(ChatAsset::className());
        return $this->render('chatWindow');
    }

}