<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.10.15
 * Time: 14:20
 */

namespace common\components;


use yii\base\Component;

class Cart extends Component{

    public $session;
    public $cartCode = '';
    public $items = [];

    public function init(){
        $this->cartCode = isset($_COOKIE['cartCode']) ? $_COOKIE['cartCode'] : '';

        $this->load();

        if(!empty($this->cartCode) && !\Yii::$app->cache->exists('cart-'.$this->cartCode.'/items')) {

        }
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

    public function save(){
        \Yii::$app->cache->set('cart-'.$this->cartCode.'/items', $this->items);
    }

    public function load(){
        $this->items = \Yii::$app->cache->get('cart-'.$this->cartCode.'/items');
    }

    public function has($itemID){
        return isset($this->items[$itemID]);
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
            $this->save();
        }

        return $this->items[$itemID];
    }
}