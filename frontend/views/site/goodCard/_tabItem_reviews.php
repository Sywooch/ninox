<div class="reviews-tab">
<div class="customer-reviews">
    <div>
        <span class="semi-bold">
           <?=_("Отзывы покупателей")?>
        </span>
        <div class="write-review">
           <?=_("Напишите отзыв")?>
        </div>
        <a class="review-number">2</a>
    </div>
    <div class="customer-review">
        <div class="reviewer-name semi-bold">Валентина Блондинка</div>
        <div class="review-data">21 декабря 2015 г.</div>
        <span>Познакомьтесь с Axure RP Pro - программой для создания прототипов ваших веб-сайтов,
            их отладки и последующей публикации. Все действия в программе наглядны и удобны, т.к.
            проектирование не требует от вас знаний по веб-программированию, вам достаточно перетаскивать
            и компоновать элементы мышкой, назначая на них различные действия и редактируя атрибуты.
        </span>
        <div class="review-answer">Ответить</div>

    </div>
    <div class="customer-review">
        <div class="reviewer-name semi-bold">Валентина Блондинка</div>
        <div class="review-data">21 декабря 2015 г.</div>
        <span>Познакомьтесь с Axure RP Pro - программой для создания прототипов ваших веб-сайтов,
            их отладки и последующей публикации. Все действия в программе наглядны и удобны, т.к.
            проектирование не требует от вас знаний по веб-программированию, вам достаточно перетаскивать
            и компоновать элементы мышкой, назначая на них различные действия и редактируя атрибуты.
        </span>
        <div class="review-answer">1 ответ</div>

    </div>

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
            } ?>
        </div>
    <?php }} */?>