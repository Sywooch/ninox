<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.11.15
 * Time: 18:01
 */

namespace backend\models;


use common\models\PriceRuleTerm;

class Pricerule extends \common\models\Pricerule{

	/**
     * @return PriceRuleTerm[] array
     */
    public static function terms(){
        $term = new PriceRuleTerm();

        return $term->getTermCategories();
    }

    public static function actions(){
        return [
            ['Discount' => 'Скидка', 'float', ['=']],
            ['Type' => 'Тип', 'integer', ['=']],
        ];
    }

}