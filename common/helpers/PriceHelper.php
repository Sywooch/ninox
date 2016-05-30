<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 23.03.16
 * Time: 12:30
 */

namespace common\helpers;


trait PriceHelper
{

    protected $_discountTypeSize = '1';
    protected $_discountTypePercent = '2';
    protected $_discountTypeSetPrice = '3';

    final public function getWholesalePrice(){
        return $this->calcPrice($this->realWholesalePrice);
    }

    final public function getRetailPrice(){
        return $this->calcPrice($this->realRetailPrice);
    }

    protected function calcPrice($price){
        switch($this->discountType){
            case $this->_discountTypePercent:
                return round($price - ($price / 100 * $this->discountSize), 2);
                break;
            case $this->_discountTypeSize:
                return $price - $this->discountSize;
                break;
            case $this->_discountTypeSetPrice:
                return $this->discountSize;
                break;
            default:
                return $price;
                break;
        }
    }
}