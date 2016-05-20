<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 04.05.16
 * Time: 14:30
 */
use yii\bootstrap\Html;
use yii\widgets\ListView;


?>
<span class="title">Отзывы</span>
<span class="text">
     <!-- --><?php /*foreach($model->review as $review => $value){
            echo Html::tag('div', $review, ['class' => 'option-name']).Html::tag('div', $value,
                    ['class' => 'option-value']);
      } */?>
      Все получили, абсолютно всем довольны ( качеством обслуживания , качеством товара) - успели к дню Святого Николая сделать своим детям сюрпризы. Большое спасибо, удачи в работе и с праздниками Вас !!!
</span>
<a href="" class="read-all-reviews">Читать все отзывы</a>
<?= Html::a(Html::button('Оставить отзыв', [
'type'  =>  'submit',
'class' =>  'green-button-new middle-button ',
    'data-target' => '#reviewModal'
]), '#reviewModal');?>