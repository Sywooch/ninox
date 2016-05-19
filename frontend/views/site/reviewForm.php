<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 04.05.16
 * Time: 14:30
 */
use yii\bootstrap\Html;


?>
<span class="title">Отзывы</span>
<span class="text">
    Каждый покупатель акционных ежедневников и планингов получает в
    подарок шариковую ручку BIC Atlantis! Каждый покупатель акционных
    ежедневников и планингов получает в подарок шариковую ручку BIC Atlantis!
</span>
<a href="">Читать все отзывы</a>
<?= Html::a(Html::button('Оставить отзыв', [
'type'  =>  'submit',
'class' =>  'green-button-new middle-button ',
    'data-target' => '#reviewModal'
]), '#reviewModal');?>