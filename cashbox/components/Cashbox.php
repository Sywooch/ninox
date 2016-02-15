<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 28.12.15
 * Time: 13:51
 */

namespace cashbox\components;

use backend\models\Customer;
use cashbox\models\CashboxOrder;
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

    /**
     * @deprecated
     * @use $cashboxOrder
     */
    public $order;


    /**
     * @type \cashbox\models\CashboxOrder
     */
    public $cashboxOrder = null;

    /**
     * @type \cashbox\models\Order
     */
    public $createdOrder = null;

    /**
     * @type \yii\caching\Cache
     */
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
        $this->cache = \Yii::$app->cache;
        $cookies = \Yii::$app->request->cookies;

        //Инициализация объектов заказов
        $this->cashboxOrder = new CashboxOrder;
        //$this->createdOrder = new Order;

        //Если в куках хранится текущая ценовая группа заказа, записываем это в заказ
        if($cookies->has('cashboxPriceType')){
            $this->priceType = $cookies->getValue('cashboxPriceType', 0);
        }

        //Если в куках хранится номер заказа, сохраняем его в заказе
        if($cookies->has("cashboxOrderID")){
            $this->orderID = $cookies->getValue("cashboxOrderID");
        }

        //Устанавливаем покупателя к заказу
        $this->setCustomer($cookies->getValue("cashboxCurrentCustomer", false));

        //Устанавливаем менеджера к заказу
        if($cookies->has('cashboxManager')){
            $this->responsibleUser = $cookies->getValue("cashboxManager", 0);
        }

        //Если заказ не пустой
        if(!empty($this->orderID)){
            //Пробуем загрузить его в память
            $this->load();
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
     * Устанавливает ID клиента
     *
     * @param $customerID integer - ID клиента
     *
     * @return integer - ID клиента
     */
    public function setCustomer($customerID){
        $config = !empty(\Yii::$app->params['configuration']) ? \Yii::$app->params['configuration'] : false;

        if($customerID){
            $this->customer = $customerID;
        }

        if($config && (empty($this->customer) || in_array($this->customer, [$config->defaultWholesaleCustomer, $config->defaultCustomer]))){
            if($this->customer == $config->defaultCustomer && $this->isWholesale()){
                $this->customer = $config->defaultWholesaleCustomer;
            }else{
                $this->customer = $config->defaultCustomer;
            }
        }

        return $this->customer;
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
     * Загружает данные о заказе
     */
    public function load(){
        if($this->cache->exists('cashbox-'.$this->orderID.'/items')){
            $this->items = \Yii::$app->cache->get('cashbox-'.$this->orderID.'/items');
        }else{
            $this->items = $this->cashboxItemsQuery()->all();
        }

        if($this->cache->exists('cashbox-'.$this->orderID.'/goods')){
            $this->goods = $this->cache->get('cashbox-'.$this->orderID.'/goods');
        }else{
            foreach($this->goodsQuery()->each() as $good){
                $this->goods[$good->ID] = $good;
            }
        }

        $cashboxOrder = CashboxOrder::findOne($this->orderID);

        if($cashboxOrder){
            $this->cashboxOrder = $cashboxOrder;
            $this->loadCashboxOrder($this->cashboxOrder);
        }
    }

    /**
     * Чистит кэш
     */
    public function clearCache(){
        $this->cache->delete('cashbox-'.$this->orderID.'/items');
        $this->cache->delete('cashbox-'.$this->orderID.'/goods');
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

        $this->cache->set('cashbox-'.$this->orderID.'/items', $this->items, 1200);
        $this->cache->set('cashbox-'.$this->orderID.'/goods', $this->goods, 1200);
    }

    /**
     * Загружает данные из модели в компонент
     *
     * @param $model CashboxOrder static
     *
     * @deprecated
     * @throws \yii\base\ErrorException
     */
    public function loadInfo($model){
        return $this->loadCashboxOrder($model);
    }

    /**
     * Загружает инфо о заказе из модели CashboxOrder
     *
     * @param $model \cashbox\models\CashboxOrder
     *
     * @throws \yii\base\ErrorException в случае если передан не CashboxOrder
     *
     * @todo проверить чтобы эта функция делала не меньше чем она должна делать
     */
    public function loadCashboxOrder($model){
        if($model instanceof CashboxOrder == false){
            throw new ErrorException("Передан неверный объект!");
        }

        $this->customer = $model->customerID;
        $this->responsibleUser = $model->responsibleUser;
        $this->priceType = $model->priceType;
        $this->promoCode = $model->promoCode;

        $this->cashboxOrder = $model;

        $this->save();
    }

    /**
     * Меняет тип цен
     */
    public function changePriceType(){
        $this->priceType = $this->priceType == 1 ? 0 : 1;

        if($this->cashboxOrder){
            $this->cashboxOrder->priceType = $this->priceType;

            $this->cashboxOrder->save(false);

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
        $this->items = $this->cashboxOrder->getItems();

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


    /**
     * @return \yii\db\ActiveQuery
     * @deprecated use $this->cashboxItemsQuery()
     */
    public function itemsQuery(){
        return CashboxItem::find()->where(['orderID' => $this->orderID]);
    }

    /**
     * Возвращает запрос для получения товаров из текущего заказа
     *
     * @return \yii\db\ActiveQuery
     */
    public function cashboxItemsQuery(){
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
     *
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

        if($this->cashboxOrder){
            $this->cashboxOrder->responsibleUser = $this->responsibleUser;

            $this->cashboxOrder->save(false);
        }

        return $this->responsibleUser;
    }

    /**
     * Возвращает товары из заказа на склад
     */
    public function refund(){
        $refundSum = $this->sum;

        foreach($this->items as $item){
            if($this->remove($item->itemID)){
                $good = Good::findOne($item->itemID);

                $good->count += $item->count;

                $good->save(false);
            }
        }

        $this->cashboxOrder->doneTime = date('Y-m-d H:i:s');
        $this->cashboxOrder->return = 1;

        if($this->cashboxOrder->save(false)){
            $refund = new CashboxMoney([
                'cashbox'   =>  \Yii::$app->params['configuration']->ID,
                'amount'    =>  $refundSum,
                'operation' =>  CashboxMoney::OPERATION_REFUND,
                'order'     =>  $this->cashboxOrder->createdOrder,
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

        foreach($this->cashboxOrder->getItems() as $item){
            $AssemblyItem = new AssemblyItem();
            $AssemblyItem->loadCashboxItem($item, $createdOrder->id);

            if($AssemblyItem->save(false)){
                $item->changedValue = 0;
                $item->delete();
            }

            $this->cashboxOrder->createdOrder = $createdOrder->id;
        }

        $this->cashboxOrder->save(false);
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
        if(empty($this->cashboxOrder)){
            throw new NotFoundHttpException("Невозможно оформить несуществующий заказ!");
        }

        if(!empty($this->cashboxOrder->createdOrder)){
            return $this->edit($this->cashboxOrder->createdOrder, $amount);
        }

        $order = new Order([
            'responsibleUserID' =>  $this->cashboxOrder->responsibleUser,
            'customerID'        =>  $this->cashboxOrder->customerID,
            'originalSum'       =>  $this->cashboxOrder->sum,
            'coupon'            =>  $this->promoCode,
            'sourceType'        =>  Order::SOURCETYPE_SHOP,
            'orderSource'       =>  \Yii::$app->params['configuration']->store,
            'sourceInfo'        =>  \Yii::$app->params['configuration']->ID,
        ]);

        if($this->cashboxOrder->customerID != 0){
            $customer = Customer::findOne(['ID' => $this->cashboxOrder->customerID]);

            $order->loadCustomer($customer);
        }

        $order->actualAmount = $amount;

        if($order->save(false)){
            foreach($this->cashboxOrder->getItems() as $item){
                $AssemblyItem = new AssemblyItem();
                $AssemblyItem->loadCashboxItem($item, $order->id);

                if($AssemblyItem->save(false)){
                    $item->changedValue = 0;
                    $item->delete();
                }

                $this->cashboxOrder->createdOrder = $order->id;
            }

            $this->cashboxOrder->doneTime = date('Y-m-d H:i:s');

            $payment = new CashboxMoney([
                'cashbox'   =>  \Yii::$app->params['configuration']->ID,
                'amount'    =>  $this->toPay,
                'operation' =>  CashboxMoney::OPERATION_SELL,
                'order'     =>  $this->cashboxOrder->createdOrder,
                'date'      =>  date('Y-m-d H:i:s'),
                'customer'  =>  $this->customer,
                'responsibleUser'   =>  $this->responsibleUser
            ]);;

            $payment->save(false);
            $this->cashboxOrder->save(false);

            $createdOrder = $this->cashboxOrder->createdOrder;

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

        $this->cashboxOrder = $this->orderID = null;

        \Yii::$app->response->cookies->remove('cashboxOrderID');
        \Yii::$app->response->cookies->remove('cashboxCurrentCustomer');
    }

    public function put($itemID, $count = 1){
        if(!$this->cashboxOrder && !empty($this->orderID)){
           $this->cashboxOrder = CashboxOrder::findOne($this->orderID);
        }elseif(!$this->cashboxOrder){
            $this->cashboxOrder = new CashboxOrder([
                'promoCode' =>  $this->promoCode
            ]);
        }

        if($this->cashboxOrder->isNewRecord){
            $this->cashboxOrder->createdTime = date('Y-m-d H:i:s');
            $this->cashboxOrder->priceType = $this->priceType;

            if(!empty($this->customer)){
                $this->cashboxOrder->customerID = $this->customer;
            }

            if($this->cashboxOrder->save(false)){
                \Yii::$app->response->cookies->add(new Cookie([
                    'name'      =>  'cashboxOrderID',
                    'value'     =>  $this->cashboxOrder->id
                ]));
            }
        }

        if(!isset($this->goods[$itemID])){
            $this->goods[$itemID] = Good::find()->where(['ID'   =>  $itemID])->one();
        }

        $good = $this->goods[$itemID];

        if(!isset($this->items[$itemID])){
            $this->items[$itemID] = new CashboxItem([
                'orderID'       =>  $this->cashboxOrder->id,
                'itemID'        =>  $good->ID,
                'category'      =>  Category::find()->select("Code")->where(['ID' => $good->GroupID])->scalar(),
                'name'          =>  $good->Name,
                'originalPrice' =>  $this->priceType == 1 ? $good->PriceOut1 : $good->PriceOut2,
            ]);

            if($this->cashboxOrder->promoCode){
                $priceRuleHelper = new PriceRuleHelper();
                $this->items[$itemID] = $priceRuleHelper->recalcSborkaItem($this->items[$itemID], Pricerule::findOne(Promocode::find()->select('rule')->where(['code' => $this->cashboxOrder->promoCode])->scalar()));
            }

            $this->cashboxOrder->_items[$itemID] = $this->items[$itemID];
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
