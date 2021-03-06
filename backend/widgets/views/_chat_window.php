<div class="chat">
    <?php
    $chatData->messagesCount = $messagesDataProvider->getCount();

    echo$this->render('_chat_header', [
        'chat'  =>  $chatData
    ])?> <!-- end chat-header -->

    <div class="chat-history">
        <ul>
            <?php
            \yii\widgets\Pjax::begin();
            echo \yii\widgets\ListView::widget([
                'dataProvider'  =>  $messagesDataProvider,
                'emptyText'     =>  'Сообщений пока что нет',
                'summary'       =>  '',
                'itemOptions'   =>  [
                    'tag'   =>  'li',
                    'class' =>  'item clearfix'
                ],
                'itemView'      =>  function($model){
                    return $this->render('_chat_message', [
                        'model'  =>  $model
                    ]);
                }
            ]);
            \yii\widgets\Pjax::end();
            ?>
        </ul>
        <ul style="display: none;">


            <li>
                <div class="message-data">
                    <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                    <span class="message-data-time">10:12 AM, Today</span>
                </div>
                <div class="message my-message">
                    Are we meeting today? Project has been already finished and I have results to show you.
                </div>
            </li>

            <li class="clearfix">
                <div class="message-data align-right">
                    <span class="message-data-time" >10:14 AM, Today</span> &nbsp; &nbsp;
                    <span class="message-data-name" >Olia</span> <i class="fa fa-circle me"></i>

                </div>
                <div class="message other-message float-right">
                    Well I am not sure. The rest of the team is not here yet. Maybe in an hour or so? Have you faced any problems at the last phase of the project?
                </div>
            </li>

            <li>
                <div class="message-data">
                    <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                    <span class="message-data-time">10:20 AM, Today</span>
                </div>
                <div class="message my-message">
                    Actually everything was fine. I'm very excited to show this to our team.
                </div>
            </li>

            <li>
                <div class="message-data">
                    <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                    <span class="message-data-time">10:31 AM, Today</span>
                </div>
            </li>

        </ul>

    </div> <!-- end chat-history -->

    <div class="chat-message clearfix">
        <textarea name="message-to-send" id="message-to-send" placeholder ="Введите сообщение" rows="3"></textarea>

        <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
        <i class="fa fa-file-image-o"></i>

        <button>Отправить</button>

    </div> <!-- end chat-message -->

</div> <!-- end chat -->