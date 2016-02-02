<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 01.02.16
 * Time: 14:34
 */

use yii\helpers\Html;

$itemsHtml = '';

shuffle($items);
foreach($items as $img){
    $items[] = Html::img($img);
}

echo Html::img($img);