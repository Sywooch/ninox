<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.11.15
 * Time: 18:01
 */

namespace backend\models;


class Review extends \common\models\Term{

    /**
     * Создает (восстанавливает) ценовое правило из массива
     *
     * @param $array
     */
    public function fromArray($array){

    }

    public static function terms(){
        return [
            ['Сумма заказа' => 'DocumentSum', 'number', ['>=', '<=', '=']],
            ['Категория товара' => 'GoodGroup', 'string', ['>=', '<=', '=', '!=']],
            ['Без пометки "распродажа"' => 'WithoutBlyamba', ['Да' => 'true'], ['=']],
            ['Дата' => 'Date', 'date', ['>=', '<=', '=']],
        ];
    }

}