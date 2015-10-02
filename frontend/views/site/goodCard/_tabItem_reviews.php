<span class="tabTitle semi-bold">Отзывы покупателей
    <span class="rewsAnswer rewsToGood" data-rews-target="<?=$good->ID?>" data-rews-type="1">Оставить отзыв</span>
    <?php /*if(!empty($rewsList)){ ?> <span class="rewsQnt"> <?=sizeof($rewsList)?></span><?php }*/ ?>
</span>
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