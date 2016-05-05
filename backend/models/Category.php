<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 02.03.16
 * Time: 13:29
 */

namespace backend\models;

/**
 * Class Category
 * @package backend\models
 * @author  Nikolai Gilko   <n.gilko@gmail.com>
 * @property Good[] $goods
 * @property Category[] $parents
 * @property Category[] $childs
 */
class Category extends \common\models\Category
{

    public function getGoods(){
        return $this->hasMany(Good::className(), ['GroupID' => 'ID']);
    }

    public function getChilds(){
        $len = strlen($this->Code) + 3;

        return self::find()
            ->where(['like', 'Code', $this->Code.'%', false])
            ->andWhere("LENGTH(`Code`) = '{$len}'")
            ->all();
    }

    /**
     * @param float $size       - размер скидки
     * @param string $priceType - тип цены
     * @param int $type         - 2 это процент
     *
     * @return bool
     */
    public function updatePrices($size, $priceType = 'all', $type = 2){
        foreach($this->goods as $good){
            $prices = [];

            switch($priceType){
                case 'wholesale':
                    $prices[] = 'PriceOut1';
                    break;
                case 'retail':
                    $prices[] = 'PriceOut2';
                    break;
                case 'all':
                default:
                    $prices = ['PriceOut1', 'PriceOut2'];
                    break;
            }

            foreach($prices as $price){
                switch($type){
                    case 1:
                        $good->$price = $good->$price + $size;
                        break;
                    case 2:
                        $good->$price = $good->$price + ($good->$price / 100 * $size);
                        break;
                }
            }

            if(!$good->save(false)){
                return false;
            }
        }

        return true;
    }

}