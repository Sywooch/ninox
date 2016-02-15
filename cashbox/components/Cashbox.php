<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.12.15
 * Time: 13:51
 */

namespace cashbox\components;

use cashbox\models\CashboxOrder;
use backend\models\Customer;
use cashbox\models\AssemblyItem;
use cashbox\models\CashboxItem;
use cashbox\models\Order;
use cashbox\helpers\PriceRuleHelper;
use common\models\CashboxMoney;
use common\models\Good;
use common\models\Category;
use common\models\Pricerule;
use common\models\Promocode;
use common\models\Siteuser;
use yii\base\Component;
use yii\base\ErrorException;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class Cashbox extends Component{

    /**
     * @property \yii\caching\Cache $cache The cache application component. Null if the component is not enabled.
     */

    public $orderID;

    public $sum = 0;
    public $sumWithoutDiscount = 0;
    public $retailSum = 0;
    public $wholesaleSum = 0;

    public $toPay = 0;

    public $customer = false;
    public $responsibleUser = 0;

    public $promoCode = false;

    /**
     * @type array
     * @var array $items CashboxItem[]
     */
    public $items = [];

    /**
     * @type array
     * @var array $goods Good[]
     */
    public $goods = [];
    public $itemsCount = 0;
    public $priceType = 0;
    public $discountSize = 0;

    public $order;

    private $cache;

    /**
     * @throws \yii\base\ErrorException
     * Инициализация компоненты
     * Во время инициализации проверяется:
     *  * выбран-ли покупатель
     *  ** если покупатель не выбран - выбирается дефолтный
     *  * есть-ли кука с cashboxOrderID
     *  ** если есть - устанавливается $this->orderID этой кукой
     *  * есть-ли кука с ID текущего клиента
     *  ** если есть - устанавливается ID клиента этой кукой
     *
     * дальше метод проверяет, чтобы все необходимые нам товары присутствовали в переменной $this->goods
     */
    public function init(){
        $cache = $this->cache = \Yii::$app->cache;

        if(empty($this->customer) && !empty(\Yii::$app->params['configuration'])){
            $this->customer = $this->isWholesale() ? \Yii::$app->params['configuration']->defaultWholesaleCustomer : \Yii::$app->params['configuration']->defaultCustomer;
        }

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

            if(!$this->cache->exists('cashbox-'.$this->orderID.'/items') || $lastUpdate > (time() + 1200)){
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

        //Делаем проверку на существование всех товаров в локальной переменной товаров
        foreach($this->items as $item){
            if(empty($this->goods[$item->itemID])){
                $this->goods[$item->itemID] = Good::findOne($item->itemID);
            }
        }

        $this->recalculate();

        $this->save();
    }

    /**
     * Возвращает тип заказа
     *
     * @return bool - оптовый заказ
     */
    public function isWholesale(){
        return $this->priceType == 1;
    }

    /**
     * Загружает данные из кэша
     */
    public function load(){
        if($this->cache->exists('cashbox-'.$this->orderID.'/items')){
            $this->items = \Yii::$app->cache->get('cashbox-'.$this->orderID.'/items');
        }

        if($this->cache->exists('cashbox-'.$this->orderID.'/items')){
            $this->goods = \Yii::$app->cache->get('cashbox-'.$this->orderID.'/goods');
        }

        if($this->cache->exists('cashbox-'.$this->orderID.'/items')){
            $this->order = \Yii::$app->cache->get('cashbox-'.$this->orderID.'/info');
        }
    }

    /**
     * Чистит кэш
     */
    public function clearCache(){
        \Yii::$app->cache->delete('cashbox-'.$this->orderID.'/items');
        \Yii::$app->cache->delete('cashbox-'.$this->orderID.'/goods');
        \Yii::$app->cache->delete('cashbox-'.$this->orderID.'/info');
    }


    /**
     * Сохраняет данные в кэш
     */
    public function save(){
        if(!empty($this->items)){
            foreach($this->items as $key => $item){
                $item->changedValue = 0;
                $this->items[$key] = $item;
            }
        }

        \Yii::$app->cache->set('cashbox-'.$this->orderID.'/items', $this->items);
        \Yii::$app->cache->set('cashbox-'.$this->orderID.'/goods', $this->goods);
        \Yii::$app->cache->set('cashbox-'.$this->orderID.'/info', $this->order);
    }

    /**
     * Загружает данные из модели в компонент
     *
     * @param $model CashboxOrder static
     *
     * @throws \yii\base\ErrorException
     */
    public function loadInfo($model){
        if($model instanceof CashboxOrder == false){
            throw new ErrorException("Передан неверный объект!");
        }

        $this->customer = $model->customerID;
        $this->responsibleUser = $model->responsibleUser;
        $this->priceType = $model->priceType;
        $this->promoCode = $model->promoCode;

        $this->order = $model;

        $this->save();
    }

    /**
     * Меняет тип цен
     */
    public function changePriceType(){
        $this->priceType = $this->priceType == 1 ? 0 : 1;

        if(!empty($this->order)){
            $this->order->priceType = $this->priceType;

            $this->order->save(false);

            $this->updateItems();
        }

        \Yii::$app->response->cookies->add(new Cookie([
            'name'      =>  'cashboxPriceType',
            'value'     =>  $this->priceType
        ]));
    }

    /**
     * Обновляет товары
     */
    public function updateItems(){
        $this->items = $this->order->getItems();

        $this->clearCache();

        $itemsIDs = [];
        $this->goods = [];

        foreach($this->items as $item){
            $itemsIDs[] = $item->itemID;
        }

        foreach(Good::find()->where(['in', 'ID', $itemsIDs])->each() as $good){
            $this->goods[$good->ID] = $good;
        }

        $this->recalculate();

        $this->save();
    }

    public function itemsQuery(){
        return CashboxItem::find()->where(['orderID' => $this->orderID]);
    }

    /**
     * Возвращает запрос для получения товаров
     *
     * @return \yii\db\ActiveQuery
     */
    public function goodsQuery(){
        $items = [];
        $find = Good::find();

        foreach($this->items as $item){
            $items[] = $item->itemID;
        }

        $find->where(['in', 'ID', $items]);

        return $find;
    }

    /**
     * Удаляет товар из заказа в кассе
     *
     * @param $itemID - ID товара
     * @param bool $return - необходимо возвратить удалённый товар на склад
     *
     * @return bool - удалён-ли товар
     */
    public function remove($itemID, $return = true){
        unset($this->items[$itemID], $this->goods[$itemID]);

        $item = CashboxItem::findOne(['orderID' => $this->orderID, 'itemID' => $itemID]);

        if($item){
            $item->return = $return;
            $item->delete();
        }

        $this->save();
        $this->recalculate();

        return true;
    }

    /**
     * Меняет ответственного за заказ на выбраного пользователя
     * @param $id - ID пользователя
     *
     * @return bool изменён-ли менеджер
     * @throws \yii\web\NotFoundHttpException - если не найден менеджер
     */
    public function changeManager($id){
        if($id != 0 && !Siteuser::findOne($id)){
            throw new NotFoundHttpException("Менеджер не найден!");
        }

        $this->responsibleUser = $id;

        \Yii::$app->response->cookies->add(new Cookie([
            'name'      =>  'cashboxManager',
            'value'     =>  $this->responsibleUser
        ]));

        if($this->order){
            $this->order->responsibleUser = $this->responsibleUser;

            $this->order->save(false);
        }

        return true;
    }

    public function refund(){
        $refundSum = $this->sum;

        foreach($this->items as $item){
            if($this->remove($item->itemID)){
                $good = Good::findOne($item->itemID);

                $good->count += $item->count;

                $good->save(false);
            }
        }

        $this->order->doneTime = date('Y-m-d H:i:s');
        $this->order->return = 1;

        if($this->order->save(false)){
            $refund = new CashboxMoney([
                'cashbox'   =>  \Yii::$app->params['configuration']->ID,
                'amount'    =>  $refundSum,
                'operation' =>  CashboxMoney::OPERATION_REFUND,
                'order'     =>  $this->order->createdOrder,
                'date'      =>  date('Y-m-d H:i:s'),
                'customer'  =>  $this->customer,
                'responsibleUser'   =>  $this->responsibleUser
            ]);

            $refund->save(false);
        }

        $this->clear();
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

    public function edit($orderID, $amount){
        $createdOrder = Order::findOne($orderID);

        if(!$createdOrder){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        $createdOrder->loadCashboxOrder($this->order, $amount);

        AssemblyItem::deleteAll(['orderID' => $createdOrder->id]);

        foreach($this->order->getItems() as $item){
            $AssemblyItem = new AssemblyItem();
            $AssemblyItem->loadCashboxItem($item, $createdOrder->id);

            if($AssemblyItem->save(false)){
                $item->changedValue = 0;
                $item->delete();
            }

            $this->order->createdOrder = $createdOrder->id;
        }

        $this->order->save(false);
        $createdOrder->save(false);

        $this->clear();

        return $createdOrder->id;
    }

    /**
     * @param $priceRule \common\models\Pricerule
     *
     * @return integer число товаров, которые были пересчитаны
     * @throws \ErrorException
     */
    public function addDiscount($priceRule){
        if($priceRule instanceof Pricerule == false){
            throw new \ErrorException();
        }

        $priceRuleHelper = new PriceRuleHelper;

        $updatedItems = 0;

        foreach($this->items as $key => $item){
            $item = $priceRuleHelper->recalcSborkaItem($item, $priceRule);
            $item->save(false);

            if($item->priceModified){
                $updatedItems++;
            }

            $this->items[$key] = $item;
        }

        $this->updateItems();

        return $updatedItems;
    }

    public function sell($amount){
        if(empty($this->order)){
            throw new NotFoundHttpException("Невозможно оформить несуществующий заказ!");
        }

        if(!empty($this->order->createdOrder)){
            return $this->edit($this->order->createdOrder, $amount);
        }

        $order = new Order([
            'responsibleUserID' =>  $this->order->responsibleUser,
            'customerID'        =>  $this->order->customerID,
            'originalSum'       =>  $this->order->sum,
            'coupon'            =>  $this->promoCode,
            'sourceType'        =>  Order::SOURCETYPE_SHOP,
            'orderSource'       =>  \Yii::$app->params['configuration']->store,
            'sourceInfo'        =>  \Yii::$app->params['configuration']->ID,
        ]);

        if($this->order->customerID != 0){
            $customer = Customer::findOne(['ID' => $this->order->customerID]);

            $order->loadCustomer($customer);
        }

        $order->actualAmount = $amount;

        if($order->save(false)){
            foreach($this->order->getItems() as $item){
                $AssemblyItem = new AssemblyItem();
                $AssemblyItem->loadCashboxItem($item, $order->id);

                if($AssemblyItem->save(false)){
                    $item->changedValue = 0;
                    $item->delete();
                }

                $this->order->createdOrder = $order->id;
            }

            $this->order->doneTime = date('Y-m-d H:i:s');

            $payment = new CashboxMoney([
                'cashbox'   =>  \Yii::$app->params['configuration']->ID,
                'amount'    =>  $this->toPay,
                'operation' =>  CashboxMoney::OPERATION_SELL,
                'order'     =>  $this->order->createdOrder,
                'date'      =>  date('Y-m-d H:i:s'),
                'customer'  =>  $this->customer,
                'responsibleUser'   =>  $this->responsibleUser
            ]);;

            $payment->save(false);
            $this->order->save(false);

            $createdOrder = $this->order->createdOrder;

            $this->clear();

            return $createdOrder;
        }

        return false;
    }

    public function clear(){
        $this->priceType = 1;

        $this->changePriceType();

        foreach($this->items as $item){
            $this->remove($item->itemID, false);
        }

        $this->clearCache();

        $this->items = $this->goods = [];

        $this->order = $this->orderID = null;

        \Yii::$app->response->cookies->remove('cashboxOrderID');
        \Yii::$app->response->cookies->remove('cashboxCurrentCustomer');
    }

    public function put($itemID, $count = 1){
        if(!$this->order && !empty($this->orderID)){
           $this->order = CashboxOrder::findOne($this->orderID);
        }elseif(!$this->order){
            $this->order = new CashboxOrder([
                'promoCode' =>  $this->promoCode
            ]);
        }

        if($this->order->isNewRecord){
            $this->order->createdTime = date('Y-m-d H:i:s');
            $this->order->priceType = $this->priceType;

            if(!empty($this->customer)){
                $this->order->customerID = $this->customer;
            }

            if($this->order->save(false)){
                \Yii::$app->response->cookies->add(new Cookie([
                    'name'      =>  'cashboxOrderID',
                    'value'     =>  $this->order->id
                ]));
            }
        }

        if(!isset($this->goods[$itemID])){
            $this->goods[$itemID] = Good::find()->where(['ID'   =>  $itemID])->one();
        }

        $good = $this->goods[$itemID];

        if(!isset($this->items[$itemID])){
            $this->items[$itemID] = new CashboxItem([
                'orderID'       =>  $this->order->id,
                'itemID'        =>  $good->ID,
                'category'      =>  Category::find()->select("Code")->where(['ID' => $good->GroupID])->scalar(),
                'name'          =>  $good->Name,
                'originalPrice' =>  $this->priceType == 1 ? $good->PriceOut1 : $good->PriceOut2,
            ]);

            if($this->order->promoCode){
                $priceRuleHelper = new PriceRuleHelper();
                $this->items[$itemID] = $priceRuleHelper->recalcSborkaItem($this->items[$itemID], Pricerule::findOne(Promocode::find()->select('rule')->where(['code' => $this->order->promoCode])->scalar()));
            }

            $this->order->_items[$itemID] = $this->items[$itemID];
        }

        $this->items[$itemID]->count += $count;

        if($this->items[$itemID]->save(false)){
            $this->goods[$itemID] = $good;
        }

        $this->recalculate();

        $this->save();

        return $this->items[$itemID];
    }

    public function postpone(){
        if(!$this->order){
           throw new NotFoundHttpException("Нечего откладывать");
        }

        $this->order->postpone = 1;

        if($this->order->save(false)){
            $this->clear();

            return true;
        }

        return false;
    }

    public function loadOrder($id, $drop = false){
        if($this->order && !$drop){
            $this->postpone();
        }

        $this->clear();

        $order = CashboxOrder::findOne($id);

        if(!$order){
            throw new NotFoundHttpException("Чек с ID ".$id." не найден!");
        }

        $this->order = $order;

        $this->order->postpone = 0;
        $this->loadInfo($this->order);
        $this->updateItems();

        \Yii::$app->response->cookies->add(new Cookie([
            'name'      =>  'cashboxOrderID',
            'value'     =>  $this->order->id
        ]));

        $this->order->save(false);
    }

    public function loadPostpone($id){
        $this->loadOrder($id, \Yii::$app->request->post('dropOrder', false));
    }

    public function changeCustomer($customerID){
        $this->customer = $customerID;

        if($this->order){
            $this->order->customerID = $this->customer;
            $this->order->save(false);
        }

        \Yii::$app->response->cookies->add(new Cookie([
            'name'  =>  'cashboxCurrentCustomer',
            'value' =>  $this->customer
        ]));

        $this->save();
    }

    public function recalculate(){
        $this->retailSum = $this->wholesaleSum = $this->sum = $this->toPay = 0;

        foreach($this->items as $item){
            $this->retailSum += ($this->goods[$item->itemID]->PriceOut2 * $item->count);
            $this->wholesaleSum += ($this->goods[$item->itemID]->PriceOut1 * $item->count);
            $this->sum += ($item->originalPrice * $item->count);
            $this->toPay += ($item->price * $item->count);
        }

        $this->discountSize = $this->sum - $this->toPay;

        $this->itemsCount = count($this->items);
    }
}
