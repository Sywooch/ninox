<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.10.15
 * Time: 14:20
 */

namespace frontend\components;

use frontend\helpers\PriceRuleHelper;
use frontend\models\Good;
use yii\base\Component;
use yii\db\ActiveQuery;
use yii\web\Cookie;

class Cart extends Component{

    public $session;
    public $cartCode = '';
    public $items = [];
    public $goods = [];
    public $itemsCount = 0;
    public $cartWholesaleSumm = 0;
    public $cartWholesaleRealSumm = 0;
	public $cartWholesaSumWithoutDiscount = 0;
	public $cartWholesaSumNotDiscounted = 0;
    public $cartRetailSumm = 0;
    public $cartRetailRealSumm = 0;
	public $cartRetailSumWithoutDiscount = 0;
	public $cartRetailSumNotDiscounted = 0;
    public $cartSumm = 0;
    public $cartRealSumm = 0;
	public $cartSumWithoutDiscount = 0;
	public $cartSumNotDiscounted = 0;
    public $wholesale = false;

    public function init(){
        $cache = \Yii::$app->cache;

        if(\Yii::$app->request->cookies->has("cartCode")){
            $this->cartCode = \Yii::$app->request->cookies->getValue("cartCode");
        }

        $this->load();

        if(!empty($this->cartCode)) {
            $lastUpdate = time() + 12000;//$cache->exists('cart-'.$this->cartCode.'/lastUpdate') ? $cache->get('cart-'.$this->cartCode.'/lastUpdate') : time() + 1201;

            if(!$cache->exists('cart-'.$this->cartCode.'/items') || $lastUpdate > (time() + 1200)){
	            $this->items = [];
	            foreach($this->itemsQuery()->each() as $item){
                    $this->items[$item->itemID] = $item;
                }
	            $this->save();
            }

            if(!$cache->exists('cart-'.$this->cartCode.'/goods') || $lastUpdate > (time() + 1200)){
	            $this->goods = [];
	            foreach($this->goodsQuery()->each() as $good){
                    $this->goods[$good->ID] = $good;
                }
	            $this->save();
            }

            if($lastUpdate > (time() + 1200)){
                $cache->set('cart-'.$this->cartCode.'/lastUpdate', time());
            }
        }

	    $this->recalcCart();
    }

    /**
     * @param string $length
     *
     * @return string
     */
    public function createCartCode($length = '11'){
        return \Yii::$app->security->generateRandomString($length);
    }

    /**
     * @return \frontend\models\Cart
     */
    public function itemsQuery(){
        return \frontend\models\Cart::find()->where(['cartCode' => $this->cartCode])->orderBy('date DESC');
    }

    /**
     * @return ActiveQuery
     */
    public function goodsQuery(){
        $items = [];

        if(!empty($this->items)){
            foreach($this->items as $item){
                $items[] = $item->itemID;
            }
        }

        return Good::find()
	        ->where(['in', '`goods`.`ID`', $items]);
    }

    public function save(){
        \Yii::$app->cache->set('cart-'.$this->cartCode.'/items', $this->items);
        \Yii::$app->cache->set('cart-'.$this->cartCode.'/goods', $this->goods);
    }

    public function load(){
        $this->items = \Yii::$app->cache->get('cart-'.$this->cartCode.'/items');
        $this->goods = \Yii::$app->cache->get('cart-'.$this->cartCode.'/goods');
    }

    /**
     * @param $itemID
     *
     * @return int
     */
    public function has($itemID){
        return isset($this->items[$itemID]) ? $this->items[$itemID]->count : 0;
    }

    /**
     * @param int $itemID
     *
     * @return bool
     */
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

    /**
     * @param int $itemID
     * @param int $count
     *
     * @return mixed
     */
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

	public function recalcCart(){
		$this->itemsCount = !empty($this->goods) ? sizeof($this->goods) : 0;
		$this->cartWholesaleSumm = 0;
		$this->cartWholesaleRealSumm = 0;
		$this->cartWholesaSumWithoutDiscount = 0;
		$this->cartRetailSumm = 0;
		$this->cartRetailRealSumm = 0;
		$this->cartRetailSumWithoutDiscount = 0;
		if(!empty($this->goods)){
			$helper = new PriceRuleHelper();
			$helper->cartSumm = 0;
			foreach($this->goods as $good){
				$helper->cartSumm += ($good->discountType > 0 && $good->priceRuleID == 0 ? $good->wholesalePrice : $good->realWholesalePrice) * $this->items[$good->ID]->count;
			}
			foreach($this->goods as $good){
				$helper->recalc($good);
				$this->cartWholesaleSumm += $good->wholesalePrice * $this->items[$good->ID]->count;
				$this->cartWholesaleRealSumm += ($good->discountType > 0 && $good->priceRuleID == 0 ? $good->wholesalePrice : $good->realWholesalePrice) * $this->items[$good->ID]->count;
				$this->cartWholesaSumNotDiscounted += $good->discountType == 0 ? $good->realWholesalePrice * $this->items[$good->ID]->count : 0;
				$this->cartWholesaSumWithoutDiscount += $good->realWholesalePrice * $this->items[$good->ID]->count;
				$this->cartRetailSumm += $good->retailPrice * $this->items[$good->ID]->count;
				$this->cartRetailRealSumm += $good->realRetailPrice * $this->items[$good->ID]->count;
				$this->cartRetailSumNotDiscounted += $good->discountType == 0 ? $good->realRetailPrice * $this->items[$good->ID]->count : 0;
				$this->cartRetailSumWithoutDiscount += $good->realRetailPrice * $this->items[$good->ID]->count;
			}
		}

        $this->wholesale = $this->cartWholesaleRealSumm >= \Yii::$app->params['domainInfo']['wholesaleThreshold'];

		$this->cartSumm = $this->wholesale ? $this->cartWholesaleSumm : $this->cartRetailSumm;
		$this->cartRealSumm = $this->wholesale ? $this->cartWholesaleRealSumm : $this->cartRetailRealSumm;
		$this->cartSumNotDiscounted = $this->wholesale ? $this->cartWholesaSumNotDiscounted : $this->cartRetailSumNotDiscounted;
		$this->cartSumWithoutDiscount = $this->wholesale ? $this->cartWholesaSumWithoutDiscount : $this->cartRetailSumWithoutDiscount;
	}
}