<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.10.15
 * Time: 14:20
 */

namespace common\components;


use frontend\models\Good;
use yii\base\Component;

class Cart extends Component{

    public $session;
    public $cartCode = '';
    public $items = [];
    public $goods = [];
    public $itemsCount = 0;
    public $cartWholesaleSumm = 0;
    public $cartRetailSumm = 0;
    public $cartSumm = 0;

    public function init(){
        $cache = \Yii::$app->cache;

        $this->cartCode = isset($_COOKIE['cartCode']) ? $_COOKIE['cartCode'] : '';

        $this->load();

        if(!empty($this->cartCode)) {
            $lastUpdate = $cache->exists('cart-'.$this->cartCode.'/lastUpdate') ? $cache->get('cart-'.$this->cartCode.'/lastUpdate') : time() + 1201;

            if(!$cache->exists('cart-'.$this->cartCode.'/items') || $lastUpdate > (time() + 1200)){
                foreach($this->itemsQuery()->each() as $item){
                    $this->items[$item->id] = $item;
                }
            }

            if(!$cache->exists('cart-'.$this->cartCode.'/goods') || $lastUpdate > (time() + 1200)){
                foreach($this->goodsQuery()->each() as $good){
                    $this->goods[$good->ID] = $good;
                }
            }

            if($lastUpdate > (time() + 1200)){
                $cache->set('cart-'.$this->cartCode.'/lastUpdate', time());
            }
        }

        $this->itemsCount = !empty($this->goods) ? sizeof($this->goods) : 0;

        if(!empty($this->goods)){
            foreach($this->goods as $good){
                $this->cartWholesaleSumm += $good->wholesale_price * $this->items[$good->ID]->count;
                $this->cartRetailSumm += $good->retail_price * $this->items[$good->ID]->count;
            }
        }

        $this->cartSumm = $this->cartRetailSumm;
    }

    public function createCartCode($length = '11'){
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKMLNOPQRSTUVWXYZ123456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }

    public function itemsQuery(){
        return \frontend\models\Cart::find()->where(['cartCode' => $this->cartCode])->orderBy('date DESC');
    }

    public function goodsQuery(){
        $items = [];

        if(!empty($this->items)){
            foreach($this->items as $item){
                $items[] = $item->goodId;
            }
        }

        return \frontend\models\Good::find()->where(['in', 'id', $items]);
    }

    public function save(){
        \Yii::$app->cache->set('cart-'.$this->cartCode.'/items', $this->items);
        \Yii::$app->cache->set('cart-'.$this->cartCode.'/goods', $this->goods);
    }

    public function load(){
        $this->items = \Yii::$app->cache->get('cart-'.$this->cartCode.'/items');
        $this->goods = \Yii::$app->cache->get('cart-'.$this->cartCode.'/goods');
    }

    public function has($itemID){
        return isset($this->items[$itemID]) ? $this->items[$itemID]->count : false;
    }

    public function put($itemID, $count = 1){
        if(empty($this->items) && empty($this->cartCode)){

            $this->cartCode = $this->createCartCode();

            setcookie('cartCode', $this->cartCode, time() + 86400 * 365, '/');
        }

        if(isset($this->items[$itemID])){
            $this->items[$itemID]->count += $count;
        }else{
            $this->items[$itemID] = new \frontend\models\Cart([
                'count'     =>  $count,
                'goodId'    =>  $itemID,
                'cartCode'  =>  $this->cartCode
            ]);
        }

        if($this->items[$itemID]->save(false)){
            $this->goods[$itemID] = Good::findOne(['ID' => $itemID]);
            $this->save();
        }

        return $this->items[$itemID];
    }
}