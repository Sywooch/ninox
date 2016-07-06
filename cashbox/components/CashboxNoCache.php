<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.07.16
 * Time: 13:36
 */

namespace cashbox\components;


use cashbox\helpers\PriceRuleHelper;
use cashbox\models\AssemblyItem;
use cashbox\models\CashboxItem;
use cashbox\models\CashboxOrder;
use cashbox\models\Order;
use cashbox\models\Siteuser;
use common\models\CashboxMoney;
use common\models\Pricerule;
use common\models\Promocode;
use yii\base\Component;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class CashboxNoCache extends Component
{

    public $sum = 0;

    public $toPay = 0;

    public $sumWithoutDiscount = 0;

    public $discountSize = 0;

    public $retailSum = 0;

    public $wholesaleSum = 0;

    public $orderID;

    /**
     * @var CashboxOrder
     */
    public $order;

    public $customer;

    public $priceType = 1;

    public $responsibleUser = 0;

    public $promoCode;

    public function init(){
        if(empty($this->responsibleUser)){
            $this->responsibleUser = \Yii::$app->request->cookies->getValue('cashboxManager', 0);
        }

        $this->priceType = \Yii::$app->request->cookies->getValue('cashboxPriceType', $this->priceType);

        if(!empty(\Yii::$app->request->cookies->getValue('cashboxOrderID', 0))){
            $order = CashboxOrder::findOne(\Yii::$app->request->cookies->getValue('cashboxOrderID', 0));

            if($order){
                $this->loadOrder($order);
            }
        }

        if(empty($this->order)){
            $this->order = new CashboxOrder([
                'responsibleUser'   =>  $this->responsibleUser,
                'customerID'        =>  $this->customer,
                'priceType'         =>  $this->priceType,
            ]);

            if(!empty($this->customer)){
                $this->order->customerID = $this->customer;
            }

            if(!empty($this->responsibleUser)){
                $this->order->responsibleUser = $this->responsibleUser;
            }
        }

        $this->recalculate();
    }

    /**
     * Делает возврат заказа
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\base\InvalidCallException
     */
    public function refund(){
        $refundSum = $this->sum;

        $order = $this->order;

        foreach($order->items as $item) {
            if ($this->remove($item->itemID)) {
                $item->good->count += $item->count;

                $item->good->save(false);
            }
        }

        $order->doneTime = date('Y-m-d H:i:s');
        $order->return = 1;

        if($order->save(false)) {
            $refund = new CashboxMoney([
                'cashbox'           => \Yii::$app->params['configuration']->ID,
                'amount'            => $refundSum,
                'operation'         => CashboxMoney::OPERATION_REFUND,
                'order'             => $this->order->createdOrderID,
                'date'              => date('Y-m-d H:i:s'),
                'customer'          => $this->customer,
                'responsibleUser'   => $this->responsibleUser
            ]);

            $refund->save(false);
        }

        $this->clearContext();

        return $order;
    }

    /**
     * Считает скидку для товаров
     *
     * @return bool
     */
    public function calcDiscount(){
        $helper = new PriceRuleHelper();
        $helper->cartSumm = $this->sum;
        $helper->customer = $this->customer;

        foreach ($this->order->items as $item) {
            if($helper->recalc($item)){
                $item->save(false);
            }
        }

        return true;
    }

    /**
     * Считает сумму текущего заказа
     */
    public function recalculate()
    {
        $this->calcDiscount();
        $this->retailSum = $this->wholesaleSum = $this->sum = $this->toPay = 0;

        foreach ($this->order->getItems()->with('good')->each() as $item) {
            $this->retailSum += ($item->good->retailPrice * $item->count);
            $this->wholesaleSum += ($item->good->wholesalePrice * $item->count);
            $this->sum += ($item->originalPrice * $item->count);
            $this->toPay += ($item->price * $item->count);
        }

        $this->discountSize = $this->sum - $this->toPay;
    }

    /**
     * Загружает отложеный чек
     *
     * @param int $id
     *
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     * @throws \yii\base\InvalidCallException
     * @throws \yii\db\StaleObjectException
     */
    public function loadPostpone($id)
    {
        $this->loadOrder($id, \Yii::$app->request->post('dropOrder', false));
    }

    /**
     * Загружает заказ
     *
     * @param CashboxOrder|int $order
     * @param bool $drop
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\base\InvalidCallException
     * @throws \yii\db\StaleObjectException
     */
    public function loadOrder($order, $drop = false){
        if(!empty($this->order)){
            if(!$drop){
                $this->postpone();
            }else{
                $this->clear();
            }
        }

        if(filter_var($order, FILTER_VALIDATE_INT)){
            $order = CashboxOrder::findOne($order);
        }

        if(empty($order)){
            throw new NotFoundHttpException("Чек с ID {$order} не найден!");
        }

        $order->setAttributes([
            'postpone'  =>  0,
        ]);

        $this->order = $order;

        $this->setCustomer($order->customerID);
        $this->setManager($order->responsibleUser);

        $this->priceType = $order->priceType;
        $this->promoCode = $order->promoCode;



        \Yii::$app->response->cookies->add(new Cookie([
            'name'      =>  'cashboxOrderID',
            'value'     =>  $this->order->id
        ]));
    }
    
    /**
     * Устанавливает клиента к заказу
     *
     * @param $customerID
     * @throws NotFoundHttpException
     * @return integer
     */
    public function setCustomer($customerID){
        if(!empty($this->order)){
            $this->order->customerID = $customerID;

            $this->order->save(false);
        }

        return $this->customer = $customerID;
    }

    /**
     * Возвращает товар из заказа
     *
     * @param $itemID
     * @return bool|CashboxItem
     */
    public function getItem($itemID){
        if(empty($this->order)){
            return false;
        }

        return $this->order->getItem($itemID);
    }

    /**
     * Удаляет товар из заказа в кассе
     *
     * @throws \yii\web\NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     *
     * @param $itemID - ID товара
     * @param bool $return @deprecated
     *
     * @return false|int
     */
    public function remove($itemID, $return = null){
        $item = $this->order->getItem($itemID);

        if($item){
            return $item->delete();
        }

        return false;
    }

    /**
     * Добавляет товар в заказ
     *
     * @param $itemID
     * @param int $count
     * @throws \yii\base\InvalidCallException
     */
    public function put($itemID, $count = 1){
        if($this->order->isNewRecord){
            $this->order->save(false);

            \Yii::$app->response->cookies->add(new Cookie([
                'name'      =>  'cashboxOrderID',
                'value'     =>  $this->order->id
            ]));
        }

        $item = $this->order->getItem($itemID);

        if(!$item){
            $item = new CashboxItem([
                'orderID'       =>  $this->order->id,
                'itemID'        =>  $itemID,
            ]);

            $item->originalPrice = $this->priceType == 1 ? $item->good->wholesalePrice : $item->good->retailPrice;
        }

        $item->count += $count;

        $item->save(false);
    }

    /**
     * Меняет колличество товара в заказе
     *
     * @param $itemID
     * @param $count
     * @return bool
     */
    public function changeCount($itemID, $count){
        $item = $this->order->getItem($itemID);

        $item->count = $count;

        return $item->save(false);
    }

    /**
     * Устанавливает менеджера на заказ
     *
     * @param $id
     * @return integer
     * @throws \yii\base\InvalidCallException
     */
    public function setManager($id){
        if(!empty($this->order)){
            $this->order->responsibleUser = $id;

            $this->order->save(false);
        }

        \Yii::$app->response->cookies->add(new Cookie([
            'name'      =>  'cashboxManager',
            'value'     =>  $id
        ]));

        return $this->responsibleUser = $id;
    }

    public function getManager(){
        if(empty($this->responsibleUser)){
            return new Siteuser();
        }

        return Siteuser::findOne($this->responsibleUser);
    }

    /**
     * @param $priceRule \common\models\Pricerule
     *
     * @return integer число товаров, которые были пересчитаны
     * @throws \ErrorException
     */
    public function addDiscount($priceRule){
        if ($priceRule instanceof Pricerule == false) {
            throw new \ErrorException();
        }

        $priceRuleHelper = new PriceRuleHelper;

        $updatedItems = 0;

        foreach ($this->order->items as $key => $item) {
            $item = $priceRuleHelper->recalcSborkaItem($item, $priceRule);
            $item->save(false);

            if ($item->priceModified) {
                $updatedItems++;
            }
        }

        return $updatedItems;
    }

    /**
     * Позволяет получить все данные о текущем заказе необходимые пользователю
     *
     * @return array
     */
    public function getSummary(){
        $this->recalculate();

        return [
            'priceType'         =>  $this->priceType,
            'sum'               =>  $this->sum,
            'sumToPay'          =>  $this->toPay,
            'wholesaleSum'      =>  $this->wholesaleSum,
            'discountSum'       =>  $this->discountSize,
            'itemsCount'        =>  $this->order->itemsCount,
        ];
    }

    /**
     * Меняет тип заказа
     *
     * @throws \yii\base\InvalidCallException
     * @return integer Тип заказа
     */
    public function changePriceType(){
        $this->priceType = $this->priceType == 1 ? 0 : 1;

        if ($this->order) {
            $this->order->priceType = $this->priceType;

            $this->order->save(false);
        }

        \Yii::$app->response->cookies->add(new Cookie([
            'name' => 'cashboxPriceType',
            'value' => $this->priceType
        ]));

        return $this->priceType;
    }

    /**
     * Выполняет продажу
     *
     * @param $amount
     * @return bool|string
     * @throws \yii\db\StaleObjectException
     * @throws \yii\base\InvalidCallException
     * @throws \Exception
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function sell($amount){
        if (empty($this->order)) {
            throw new NotFoundHttpException('Невозможно оформить несуществующий заказ!');
        }

        $isNewOrder = empty($this->order->createdOrder);

        $order = $isNewOrder ? new Order() : $this->order->createdOrder;

        $order->setAttributes([
            'responsibleUserID'     => $this->responsibleUser,
            'customerID'            => empty($this->customer) ? 0 : $this->customer,
            'originalSum'           => $this->toPay,
            'actualAmount'          => $amount,
            'coupon'                => $this->promoCode,
            'sourceType'            => Order::SOURCETYPE_SHOP,
            'orderSource'           => \Yii::$app->params['configuration']->store,
            'sourceInfo'            => \Yii::$app->params['configuration']->ID,
        ]);

        if(!$order->save(false)){
            return false;
        }

        if($isNewOrder){
            foreach($this->order->items as $item){
                $assemblyItem = new AssemblyItem();
                $assemblyItem->loadCashboxItem($item, $order->id);

                if ($assemblyItem->save(false)) {
                    $item->changedValue = 0;
                    $item->delete();
                }
            }
        }else{
            $oldItems = $newItems = [];

            foreach($this->order->items as $item){
                $newItems[] = $item->itemID;
            }

            foreach($this->order->createdOrder->items as $item){
                $oldItems[] = $item->itemID;
            }

            /*
             * Удаление товаров из заказа
             */
            foreach(array_diff($oldItems, $newItems) as $deleteItem){
                $this->order->createdOrder->getItem($deleteItem)->delete();
            }

            /*
             * Добавление товаров в заказ
             */
            foreach(array_diff($newItems, $oldItems) as $createItem){
                $item = $this->order->getItem($createItem);

                $assemblyItem = new AssemblyItem();
                $assemblyItem->loadCashboxItem($item, $order->id);

                if ($assemblyItem->save(false)) {
                    $item->changedValue = 0;
                    $item->delete();
                }
            }
            
            foreach(array_intersect($newItems, $oldItems) as $updatedItem){
                $item = $this->order->createdOrder->getItem($updatedItem);

                $item->loadCashboxItem($this->order->getItem($updatedItem), $order->id);
                $item->save(false);
            }
        }

        $this->order->setAttributes([
            'createdOrderID'    =>  $order->id,
            'doneTime'          =>  date('Y-m-d H:i:s')
        ]);

        $createdOrder = $this->order->createdOrderID = $order->id;

        $this->order->save(false);

        $payment = false;

        if(!$isNewOrder){
            $payment = CashboxMoney::findOne(['order' => $this->order->id]);
        }

        if(!$payment){
            $payment = new CashboxMoney([
                'cashbox'           => \Yii::$app->params['configuration']->ID,
                'operation'         => CashboxMoney::OPERATION_SELL,
                'order'             => $this->order->id,
            ]);
        }

        $payment->setAttributes([
            'amount'            => $amount,
            'date'              => date('Y-m-d H:i:s'),
            'customer'          => empty($this->customer) ? 0 : $this->customer,
            'responsibleUser'   => $this->responsibleUser
        ]);

        $payment->save(false);

        $this->clearContext();

        return $createdOrder;
    }

    /**
     * Откладывает "чек"
     *
     * @return bool
     * @throws \yii\db\StaleObjectException
     * @throws \yii\base\InvalidCallException
     * @throws \Exception
     * @throws \yii\web\NotFoundHttpException
     *
     */
    public function postpone(){
        $this->order->postpone = 1;

        if($this->order->save(false)){
            $this->clearContext();

            return true;
        }

        return false;
    }

    public function clearContext(){
        $cookies = \Yii::$app->response->cookies;

        $this->promoCode = false;
        $this->order = $this->orderID = $this->customer = null;

        $cookies->remove('cashboxOrderID');
        $cookies->remove('cashboxCurrentCustomer');
    }

    /**
     * Очищает заказ от всего
     * @throws \yii\base\InvalidCallException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function clear(){
        $this->priceType = 1;

        if(!empty($this->order)){
            foreach ($this->order->items as $item) {
                $item->delete();
            }

            $this->order->delete();
        }

        $this->clearContext();
    }

    /**
     * Пересчитывает товар
     *
     * @param $itemID
     * @return bool
     */
    public function recalculateItem($itemID)
    {
        $priceRuleHelper = new PriceRuleHelper();

        $priceRuleHelper->cartSumm = $this->sum;
        $priceRuleHelper->customer = $this->customer;

        $priceRule = Pricerule::findOne(Promocode::find()->select('rule')->where(['code' => $this->order->promoCode])->scalar());

        if($priceRule){
            $priceRuleHelper->recalcSborkaItem($this->getItem($itemID), $priceRule);
            return $this->getItem($itemID)->save(false);
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function cashboxItemsQuery(){
        return $this->order->getItems();
    }

    /**
     * @return int
     */
    public function getItemsCount(){
        return $this->order->itemsCount;
    }

    /**
     * @return float
     */
    public function getToPay(){
        return $this->order->toPay;
    }
    
}