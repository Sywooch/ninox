<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.12.15
 * Time: 13:51
 */

namespace backend\components;


use backend\models\CashboxItem;
use backend\models\CashboxOrder;
use common\models\Good;
use common\models\Category;
use yii\base\Component;
use yii\base\ErrorException;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class Cashbox extends Component{

    public $orderID;

    public $sum = 0;
    public $retailSum = 0;
    public $wholesaleSum = 0;

    public $toPay = 0;

    public $customer = false;
    public $responsibleUser = 0;
    public $items = [];
    public $goods = [];
    public $itemsCount = 0;
    public $priceType = 0;
    public $discountSize = 0;

    public $order;

    public function init(){
        $cache = \Yii::$app->cache;

        if(\Yii::$app->request->cookies->has("cashboxOrderID")){
            $this->orderID = \Yii::$app->request->cookies->getValue("cashboxOrderID");
        }

        if(\Yii::$app->request->cookies->has("cashboxCurrentCustomer")){
            $this->customer = \Yii::$app->request->cookies->getValue("cashboxCurrentCustomer");
        }

        if(\Yii::$app->request->cookies->has('cashboxPriceType')){
            $this->priceType = \Yii::$app->request->cookies->getValue('cashboxPriceType', 0);
        }

        if(\Yii::$app->request->cookies->has('cashboxManager')){
            $this->responsibleUser = \Yii::$app->request->cookies->getValue("cashboxManager", 0);
        }

        if(!empty($this->orderID)){
            $this->load();

            $lastUpdate = $cache->exists('cashbox-'.$this->orderID.'/lastUpdate') ? $cache->get('cashbox-'.$this->orderID.'/lastUpdate') : time() + 1201;

            if(!$cache->exists('cashbox-'.$this->orderID.'/items') || $lastUpdate > (time() + 1200)){
                foreach($this->itemsQuery()->each() as $item){
                    $this->items[$item->itemID] = $item;
                }
            }

            if(!$cache->exists('cashbox-'.$this->orderID.'/info') || $lastUpdate > (time() + 1200)){
                $this->loadInfo(CashboxOrder::findOne($this->orderID));
            }

            if(!$cache->exists('cashbox-'.$this->orderID.'/goods') || $lastUpdate > (time() + 1200)){
                foreach($this->goodsQuery()->each() as $good){
                    $this->goods[$good->ID] = $good;
                }
            }

            if($lastUpdate > (time() + 1200)){
                $cache->set('cashbox-'.$this->orderID.'/lastUpdate', time());
            }
        }

        $this->recalculate();
    }

    public function loadInfo($model){
        if($model instanceof CashboxOrder == false){
            throw new ErrorException("Передан неверный объект!");
        }

        $this->customer = $model->customerID;
        $this->responsibleUser = $model->responsibleUser;
        $this->priceType = $model->priceType;
    }

    public function changePriceType(){
        $this->priceType = $this->priceType == 1 ? 0 : 1;

        if(!empty($this->order)){
            $this->order->priceType = $this->priceType;

            if($this->order->save(false)){
                foreach($this->items as $item){
                    $price = ($this->priceType == 1 ? $this->goods[$item->itemID]->PriceOut1 : $this->goods[$item->itemID]->PriceOut2);
                    \Yii::trace("item: ".$item->itemID.'; price: '.$price.'; oldPrice: '.$item->originalPrice);
                    $item->originalPrice = $price;
                    $item->save(false);
                }
            }
        }

        \Yii::$app->response->cookies->add(new Cookie([
            'name'      =>  'cashboxPriceType',
            'value'     =>  $this->priceType
        ]));

        $this->save();
    }

    public function itemsQuery(){
        return CashboxItem::find()->where(['orderID' => $this->orderID]);
    }

    public function goodsQuery(){
        $items = [];

        foreach($this->items as $item){
            $items[] = $item->itemID;
        }

        return Good::find()->where(['in', 'ID', $items]);
    }

    public function load(){
        $this->items = \Yii::$app->cache->get('cashbox-'.$this->orderID.'/items');
        $this->goods = \Yii::$app->cache->get('cashbox-'.$this->orderID.'/goods');
        $this->order = \Yii::$app->cache->get('cashbox-'.$this->orderID.'/info');
    }

    public function save(){
        \Yii::$app->cache->set('cashbox-'.$this->orderID.'/items', $this->items);
        \Yii::$app->cache->set('cashbox-'.$this->orderID.'/goods', $this->goods);
        \Yii::$app->cache->set('cashbox-'.$this->orderID.'/info', $this->order);
    }

    public function remove($itemID){
        unset($this->items[$itemID], $this->goods[$itemID]);

        $item = CashboxItem::findOne(['orderID' => $this->orderID, 'itemID' => $itemID]);

        if($item){
            $item->delete();
        }

        $this->save();
        $this->recalculate();

        return true;
    }

    public function changeCount($itemID, $count){
        $this->items[$itemID]->count = $count;

        if($this->items[$itemID]->save(false)){
            $this->save();

            $this->recalculate();

            return true;
        }

        return false;
    }

    public function put($itemID, $count = 1){
        if(empty($this->order) && !empty($this->orderID)){
           $this->order = CashboxOrder::findOne($this->orderID);
        }elseif(empty($this->order)){
            $this->order = new CashboxOrder();
        }

        if($this->order->isNewRecord){
            if(!empty($this->customer)){
                $this->order->customerID = $this->customer;
            }

            $this->order->createdTime = date('Y-m-d H:i:s');
            $this->order->priceType = $this->priceType;

            if($this->order->save(false)){
                \Yii::$app->response->cookies->add(new Cookie([
                    'name'      =>  'cashboxOrderID',
                    'value'     =>  $this->order->id
                ]));
            }
        }

        if(!isset($this->goods[$itemID])){
            $good = Good::find()->where(['ID'   =>  $itemID])->one();
        }else{
            $good = $this->goods[$itemID];
        }

        if(isset($this->items[$good->ID])){
            $this->items[$good->ID]->count += $count;
        }else{
            $this->items[$good->ID] = new CashboxItem([
                'orderID'       =>  $this->order->id,
                'itemID'        =>  $good->ID,
                'count'         =>  $count,
                'category'      =>  Category::find()->select("Code")->where(['ID' => $good->GroupID])->scalar(),
                'name'          =>  $good->Name,
                'originalPrice' =>  $this->priceType == 1 ? $good->PriceOut2 : $good->PriceOut1,
            ]);
        }

        if($this->items[$good->ID]->save(false) && !isset($this->goods[$good->ID])){
            $this->goods[$good->ID] = $good;
        }

        $this->recalculate();

        $this->save();

        return $this->items[$good->ID];
    }

    public function postpone(){
        if(empty($this->orderID)){
           throw new NotFoundHttpException("Нечего откладывать");
        }


    }

    /*public function removeItem($itemID, $count = 1){

    }*/

    public function recalculate(){
        $this->retailSum = $this->wholesaleSum = $this->sum = $this->toPay = 0;

        foreach($this->items as $item){
            $this->retailSum += ($this->goods[$item->itemID]->PriceOut2 * $item->count);
            $this->wholesaleSum += ($this->goods[$item->itemID]->PriceOut1 * $item->count);
            $this->sum += ($item->originalPrice * $item->count);
            $this->toPay += ($item->price * $item->count);
        }

        $this->itemsCount = count($this->items);
    }

    /*public function clear(){

    }*/

}