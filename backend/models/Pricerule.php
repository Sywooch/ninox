<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.11.15
 * Time: 18:01
 */

namespace backend\models;


class Pricerule extends \common\models\Pricerule{

    /**
     * Создает (восстанавливает) ценовое правило из массива
     *
     * @param $array
     */
    public function fromArray($array){

    }

	/**
     * @return PriceRuleTerm[] array
     */
    public static function terms(){
        return [
            new PriceRuleTerm([
                'attribute'         =>  'DocumentSum',
                'label'             =>  'Сумма заказа',
                'possibleOperands'  =>  ['>=', '<=', '=']
            ]),
            new PriceRuleTerm([
                'attribute'         =>  'GoodGroup',
                'label'             =>  'Категория товара',
                'possibleOperands'  =>  ['>=', '<=', '=', '!=']
            ]),
            new PriceRuleTerm([
                'attribute'         =>  'WithoutBlyamba',
                'label'             =>  'Без пометки "Акция"',
                'default'           =>  ['Да' => 'true'],
                'possibleOperands'  =>  ['=']
            ]),
            new PriceRuleTerm([
                'attribute'         =>  'Date',
                'label'             =>  'Дата',
                'possibleOperands'  =>  ['>=', '<=', '=']
            ]),
        ];
    }

    public static function actions(){
        return [
            ['Discount' => 'Скидка', 'float', ['=']],
            ['Type' => 'Тип', 'integer', ['=']],
        ];
    }

}