<?php use yii\bootstrap\Modal;?>
<div class="reviews-tab">
<div class="customer-reviews">
    <div>
        <span class="semi-bold">
           <?=_("Отзывы покупателей")?>
        </span>
        <div class="write-review">
            <?php
            Modal::begin([
                             'header' => $this->render('_write_review', [
                                 'good'  =>  $good
                             ]),
                             'toggleButton' => [
                                 'tag' => 'button',
                                 'class' => 'btn btn-lg btn-block btn-info',
                                 'label' => 'Напишите отзыв',
                             ]
                         ]);
            echo \yii\helpers\Html::button('Отправить', [
                'type'  =>  'submit',
                'class' =>  'yellow-button large-button ',
                'id'    =>  'submit'
            ]);
            Modal::end();
            ?>
        </div>
        <a class="review-number">2</a>
    </div>
    <div class="customer-review">
        <div class="reviewer-name semi-bold">Валентина Блондинка<!--<?/*=$goodComment->who*/?>-->
        </div>
        <div class="review-data">21 декабря 2015 г.</div>
        <span>Познакомьтесь с Axure RP Pro - программой для создания прототипов ваших веб-сайтов,
            их отладки и последующей публикации. Все действия в программе наглядны и удобны, т.к.
            проектирование не требует от вас знаний по веб-программированию, вам достаточно перетаскивать
            и компоновать элементы мышкой, назначая на них различные действия и редактируя атрибуты.
        </span>
        <div class="review-answer">Ответить</div>

    </div>
    <?php /*if(!empty($Reviews)){ ?> <span class="rewsQnt"> <?=sizeof($Reviews)?></span><?php } ?>

    <?php echo ''; if(empty($Reviews)){ ?> <div class="rewsZero">Отзывов к даному товару еще нет, Ваш отзыв будет первым.</div><?php } ?>
<?php foreach($Reviews as $rew){
        if($rew['show'] == '1'){
        $data = substr($rew['date'], 0, 10);
        $data = explode('-', $data);
        $data['1'] = Core::s_getMonthName($data['1'], $_SESSION['lang'], true);
        ?>
    <div class="customer-review">
        <div class="reviewer-name semi-bold">
            <?=$rew['who']?>
        </div>
        <div class="review-data">
            <?=$data['2']?> <?=$data['1']?> <?=$data['0']?>
        </div>
        <span>
            <?=$rew['what']?>
        </span>
        <?php if(sizeof($rew['rewToRew'])!=0){ ?>
        <div class="review-answer"><?=sizeof($rew['rewToRew']).' '.Core::plural(sizeof($rew['rewToRew']), _("ответ"), _("ответа"), _("ответов"))?></div>
        <?php }else{ ?><span data-rews-target=<?=$rew['commentID']?> data-rews-type="2">Ответить</span></br><?php } ?>
        <?php foreach($rew['rewToRew'] as $rewToRew){
            if($rewToRew['show'] == '1'){
                $data = substr($rewToRew['date'], 0, 10);
                $data = explode('-', $data);
                $data['1'] = Core::s_getMonthName($data['1'], $_SESSION['lang'], true); ?>
                <div class="RewsToRewsWrap">
                    <span class="rewsName semi-bold"><?=$rewToRew['who']?></span><span><?=$data['2']?> <?=$data['1']?> <?=$data['0']?></span><span><?=$rewToRew['what']?></span>
                </div>
            <?php }
        }?>
    </div>
    <?php }}?>


</div>
    </div>
    <?php /*if(!empty($rewsList)){ ?> <span class="rewsQnt"> <?=sizeof($rewsList)?></span><?php }*/ ?>

<?php echo '';/* if(empty($rewsList)){ ?> <div class="rewsZero">Отзывов к даному товару еще нет, Ваш отзыв будет первым.</div><?php } ?>
<?php foreach($rewsList as $rew){
    if($rew['show'] == '1'){
        $data = substr($rew['date'], 0, 10);
        $data = explode('-', $data);
        $data['1'] = Core::s_getMonthName($data['1'], $_SESSION['lang'], true);
        ?>

        <div class="RewsToGoodWrap">
            <span class="rewsName semi-bold"><?=$rew['who']?></span><span class="rewsDate"><?=$data['2']?> <?=$data['1']?> <?=$data['0']?></span><br><span class="rewsWhat"><?=$rew['what']?></span>
            <br><?php if(sizeof($rew['rewToRew'])!=0){ ?> <span class="answerControl rewsToRews"><?=sizeof($rew['rewToRew']).' '.Core::plural(sizeof($rew['rewToRew']), _("ответ"), _("ответа"), _("ответов"))?></span>
            <?php }else{ ?><span class="rewsAnswer rewsToRews" data-rews-target=<?=$rew['commentID']?> data-rews-type="2">Ответить</span></br><?php } ?>
            <?php foreach($rew['rewToRew'] as $rewToRew){
                if($rewToRew['show'] == '1'){
                    $data = substr($rewToRew['date'], 0, 10);
                    $data = explode('-', $data);
                    $data['1'] = Core::s_getMonthName($data['1'], $_SESSION['lang'], true); ?>
                    <div class="RewsToRewsWrap">
                        <span class="rewsName semi-bold"><?=$rewToRew['who']?></span><span class="rewsDate"><?=$data['2']?> <?=$data['1']?> <?=$data['0']?></span><br><span class="rewsWhat"><?=$rewToRew['what']?></span>
                    </div>
                <?php }
            } */?>
        </div>
    <?php /*}} */?>
    </div>
