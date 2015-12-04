<?php
$defaultChat = new \common\models\Chat([
    'name'      =>  'Общий чат',
    'avatar'    =>  'https://krasota-style.com.ua/template/img/ring_for_home.png',
    'id'        =>  '1'
]);
?>
<div class="chatbox" style="display: none;">
    <div class="container clearfix">
        <div class="people-list" id="people-list" style="width: 86px;">
            <!--<div class="search">
                <input type="text" placeholder="search" />
                <i class="fa fa-search"></i>
            </div>-->
            <ul class="list">
                <li class="clearfix chat-rollUp">
                    <span style="margin-left: 20px;"><?=\rmrevin\yii\fontawesome\FA::icon('arrow-left')?></span>
                </li>

                <div class="list-view">
                    <li class="clearfix" data-key="1">
                        <?=$this->render('_chat_person', [
                            'chat'  =>  $defaultChat
                        ])?>
                    </li>
                </div>
                <?=''/*\yii\widgets\ListView::widget([
                    'dataProvider'  =>  $chatsDataProvider,
                    'emptyText'     =>  '',
                    'summary'       =>  '',
                    'itemOptions'   =>  [
                        'tag'   =>  'li',
                        'class' =>  'clearfix'
                    ],
                    'itemView'      =>  function($model){
                        return $this->render('_chat_person', [
                            'chat'  =>  $model
                        ]);
                    }
                ])*/?>

                <li class="clearfix chat-createChat" style="display: none">
                    <span style="margin-left: 20px;"><?=\rmrevin\yii\fontawesome\FA::icon('plus')?></span>
                </li>
            </ul>
        </div>
        <?php if(!empty($chatsDataProvider->getModels())){
            echo $this->render('_chat_window', [
                'chatData'  =>  $defaultChat,//$chatsDataProvider->getModels()['0'],
                'messagesDataProvider'  =>  $messagesDataProvider
            ]);
        } ?>
    </div>

    <script id="message-template" type="text/x-handlebars-template">
        <li class="clearfix">
            <div class="message-data align-right">
                <span class="message-data-time" >{{time}}</span> &nbsp; &nbsp;
                <span class="message-data-name" >{{myName}}</span>
            </div>
            <div class="message other-message float-right">
                {{messageOutput}}
            </div>
        </li>
    </script>

    <script id="message-response-template" type="text/x-handlebars-template">
        <li>
            <div class="message-data">
                <span class="message-data-name">{{author}}</span>
                <span class="message-data-time">{{time}}</span>
            </div>
            <div class="message my-message">
                {{response}}
            </div>
        </li>
    </script>
</div>