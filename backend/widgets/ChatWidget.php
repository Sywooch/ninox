<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 26.11.15
 * Time: 15:17
 */

namespace backend\widgets;


use backend\assets\ChatAsset;
use common\models\Chat;
use common\models\ChatMessage;
use common\models\ChatReceiver;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class ChatWidget extends Widget{

    public function run(){
        $this->getView()->registerAssetBundle(ChatAsset::className());

        $chatsDataProvider = new ActiveDataProvider([
            'query' =>  Chat::find()->where(['in', 'id', ChatReceiver::find()->select('chat')->where(['user'    =>  \Yii::$app->user->identity->id])])
        ]);

        $messagesDataProvider = new ActiveDataProvider();

        if($chatsDataProvider->getCount() >= 1){
            $messagesDataProvider = new ActiveDataProvider([
                'query' =>  ChatMessage::find()->where(['chat'  =>  $chatsDataProvider->getModels()['0']->id])->orderBy('timestamp ASC')
            ]);
        }

        return $this->render('chatWindow', [
            'chatsDataProvider'     =>  $chatsDataProvider,
            'messagesDataProvider'  =>  $messagesDataProvider
        ]);
    }

}