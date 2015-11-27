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

                <?=\yii\widgets\ListView::widget([
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
                ])?>

                <li class="clearfix chat-createChat">
                    <span style="margin-left: 20px;"><?=\rmrevin\yii\fontawesome\FA::icon('plus')?></span>
                </li>
            </ul>
        </div>
        <?php if(!empty($chatsDataProvider->getModels())){
            echo $this->render('_chat_window', [
                'chatData'  =>  $chatsDataProvider->getModels()['0'],
                'messagesDataProvider'  =>  $messagesDataProvider
            ]);
        } ?>
    </div>

    <script id="message-template" type="text/x-handlebars-template">
        <li class="clearfix">
            <div class="message-data align-right">
                <span class="message-data-time" >{{time}}, Today</span> &nbsp; &nbsp;
                <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>
            </div>
            <div class="message other-message float-right">
                {{messageOutput}}
            </div>
        </li>
    </script>

    <script id="message-response-template" type="text/x-handlebars-template">
        <li>
            <div class="message-data">
                <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                <span class="message-data-time">{{time}}, Today</span>
            </div>
            <div class="message my-message">
                {{response}}
            </div>
        </li>
    </script>
</div>