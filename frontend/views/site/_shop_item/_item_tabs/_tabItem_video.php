<?php
foreach($videos as $video){
?>
<iframe class="item-video" width="560" height="315" src="//www.youtube.com/embed/<?=$video->video?>" frameborder="0"
         allowfullscreen></iframe>
<?php }?>