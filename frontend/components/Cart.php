<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.10.15
 * Time: 14:20
 */

namespace frontend\components;

use common\helpers\PriceRuleHelper;
use frontend\models\Good;
use yii\base\Component;
use yii\web\Cookie;

class Cart extends Component{

    public $session;
    public $cartCode = '';
    public $items = [];
    public $goods = [];
    public $itemsCount = 0;
    public $cartWholesaleSumm = 0;
    public $cartWholesaleRealSumm = 0;
    public $cartRetailSumm = 0;
    public $cartRetailRealSumm = 0;
    public $cartSumm = 0;
    public $cartRealSumm = 0;
    public $wholesale = false;

    public function init(){
        $cache = \Yii::$app->cache;

        if(\Yii::$app->request->cookies->get("cartCode")){
            $this->cartCode = \Yii::$app->request->cookies->get("cartCode");
        }

        $this->load();

        if(!empty($this->cartCode)) {
            $lastUpdate = $cache->exists('cart-'.$this->cartCode.'/lastUpdate') ? $cache->get('cart-'.$this->cartCode.'/lastUpdate') : time() + 1201;

            if(!$cache->exists('cart-'.$this->cartCode.'/items') || $lastUpdate > (time() + 1200)){
                foreach($this->itemsQuery()->each() as $item){
                    $this->items[$item->itemID] = $item;
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

	    $this->calcCart();
    }

    public function createCartCode($length = '11'){
        return \Yii::$app->security->generateRandomString($length);
    }

    public function itemsQuery(){
        return \frontend\models\Cart::find()->where(['cartCode' => $this->cartCode])->orderBy('date DESC');
    }

    public function goodsQuery(){
        $items = [];

        if(!empty($this->items)){
            foreach($this->items as $item){
                $items[] = $item->itemID;
            }
        }

        return Good::find()->
	        where(['in', '`goods`.`ID`', $items]);
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
        return isset($this->items[$itemID]) ? $this->items[$itemID]->count : 0;
    }

	public function remove($itemID){
		unset($this->items[$itemID]);
		unset($this->goods[$itemID]);
		$item = \frontend\models\Cart::findOne(['cartCode' => $this->cartCode, 'itemID' => $itemID]);

		if($item){
			$item->delete();
		}
		$this->save();
		$this->recalcCart();
		return true;
	}

    public function put($itemID, $count = 1){
        if(empty($this->items) && empty($this->cartCode)){

            $this->cartCode = $this->createCartCode(11);

            \Yii::$app->response->cookies->add(new Cookie([
                'name'      =>  'cartCode',
                'value'     =>  $this->cartCode,
                'expire'    =>  (time() + 86400 * 365)
            ]));
        }

        if(isset($this->items[$itemID])){
            $this->items[$itemID]->count += $count;
        }else{
            $this->items[$itemID] = new \frontend\models\Cart([
                'count'     =>  $count,
                'itemID'    =>  $itemID,
                'cartCode'  =>  $this->cartCode
            ]);

            if(!\Yii::$app->user->isGuest){
                $this->items[$itemID]->customerID = \Yii::$app->user->identity->ID;
            }
        }

        if($this->items[$itemID]->save(false) && empty($this->goods[$itemID])){
            $this->goods[$itemID] = Good::findOne(['`goods`.`ID`' => $itemID]);
        }

	    $this->recalcCart();

	    $this->save();

        return $this->items[$itemID];
    }

    public function isWholesale($barrier = 800){
        return $this->cartWholesaleSumm >= $barrier;
    }

	public function calcCart(){
		$this->itemsCount = !empty($this->goods) ? sizeof($this->goods) : 0;
		$this->cartWholesaleSumm = 0;
		$this->cartWholesaleRealSumm = 0;
		$this->cartRetailSumm = 0;
		$this->cartRetailRealSumm = 0;
		if(!empty($this->goods)){
			foreach($this->goods as $good){
				$this->cartWholesaleSumm += $good->wholesale_price * $this->items[$good->ID]->count;
				$this->cartWholesaleRealSumm += $good->wholesale_real_price * $this->items[$good->ID]->count;
				$this->cartRetailSumm += $good->retail_price * $this->items[$good->ID]->count;
				$this->cartRetailRealSumm += $good->retail_real_price * $this->items[$good->ID]->count;
			}
			$this->cartSumm = $this->cartRetailSumm;
			$this->cartRealSumm = $this->cartRetailRealSumm;
		}

		$this->wholesale = $this->cartWholesaleSumm >= \Yii::$app->params['domainInfo']['wholesaleThreshold'];

		$this->cartSumm = $this->wholesale ? $this->cartWholesaleSumm : $this->cartRetailSumm;
		$this->cartRealSumm = $this->wholesale ? $this->cartWholesaleRealSumm : $this->cartRetailRealSumm;
	}

	public function recalcCart(){
		$this->itemsCount = !empty($this->goods) ? sizeof($this->goods) : 0;
		$this->cartWholesaleSumm = 0;
		$this->cartWholesaleRealSumm = 0;
		$this->cartRetailSumm = 0;
		$this->cartRetailRealSumm = 0;
		if(!empty($this->goods)){
			foreach($this->goods as $good){
				$this->cartWholesaleSumm += $good->wholesale_price * $this->items[$good->ID]->count;
				$this->cartWholesaleRealSumm += $good->wholesale_real_price * $this->items[$good->ID]->count;
				$this->cartRetailSumm += $good->retail_price * $this->items[$good->ID]->count;
				$this->cartRetailRealSumm += $good->retail_real_price * $this->items[$good->ID]->count;
			}
			$this->cartSumm = $this->cartRetailSumm;
			$this->cartRealSumm = $this->cartRetailRealSumm;
			$this->cartWholesaleSumm = 0;
			$this->cartWholesaleRealSumm = 0;
			$this->cartRetailSumm = 0;
			$this->cartRetailRealSumm = 0;
			$helper = new PriceRuleHelper();
			foreach($this->goods as $good){
				$good = $helper->recalc($good);
				$this->cartWholesaleSumm += $good->wholesale_price * $this->items[$good->ID]->count;
				$this->cartWholesaleRealSumm += $good->wholesale_real_price * $this->items[$good->ID]->count;
				$this->cartRetailSumm += $good->retail_price * $this->items[$good->ID]->count;
				$this->cartRetailRealSumm += $good->retail_real_price * $this->items[$good->ID]->count;
			}
		}

        $this->wholesale = $this->cartWholesaleSumm >= \Yii::$app->params['domainInfo']['wholesaleThreshold'];

		$this->cartSumm = $this->wholesale ? $this->cartWholesaleSumm : $this->cartRetailSumm;
		$this->cartRealSumm = $this->wholesale ? $this->cartWholesaleRealSumm : $this->cartRetailRealSumm;
	}
}