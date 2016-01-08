<?php

use yii\db\Migration;
use yii\db\Schema;

class m151227_140153_oder_source_in_history extends Migration
{
    public function up()
    {
	    $this->createTable('orderPayments', [
		    'paymentID' =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL PRIMARY KEY',
		    'orderID'   =>  Schema::TYPE_BIGINT.' UNSIGNED NOT NULL DEFAULT 0',
		    'paymentType'   =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
		    'paymentParam'   =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
		    'paymentDate'   =>  Schema::TYPE_DATETIME,
		    'paymentAmount' =>  Schema::TYPE_DOUBLE.' (12, 2) UNSIGNED NOT NULL DEFAULT 0',
		    'confirmed'     =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
		    'confirmationDate'  =>  Schema::TYPE_DATETIME,
		    'INDEX `orderID` (`orderID`) USING BTREE'
	    ]);

	    $this->dropTable('paymenttypes');

	    $this->createTable('paymentTypes', [
		    'id'    =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT',
		    'description'   =>  Schema::TYPE_STRING,
		    'enabled'   =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
		    'PRIMARY KEY (`id`)'
	    ]);

	    $this->createTable('paymentParams', [
		    'id'    =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT',
		    'description'   =>  Schema::TYPE_STRING,
		    'value' =>  Schema::TYPE_STRING,
		    'option'    =>  Schema::TYPE_STRING,
		    'enabled'   =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
		    'PRIMARY KEY (`id`)'
	    ]);

	    $this->createTable('deliveryTypes', [
		    'id'    =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT',
		    'description'   =>  Schema::TYPE_STRING,
		    'enabled'   =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
		    'PRIMARY KEY (`id`)'
	    ]);

	    $this->createTable('deliveryParams', [
		    'id'    =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT',
		    'description'   =>  Schema::TYPE_STRING,
		    'option'    =>  Schema::TYPE_TEXT,
		    'enabled'   =>  Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0',
		    'PRIMARY KEY (`id`)'
	    ]);

	    $this->addColumn('history', 'orderSource', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->addColumn('history', 'sourceType', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
	    $this->addColumn('history', 'deliveryParam', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0 AFTER `deliveryType`');
	    $this->alterColumn('history', 'deliveryInfo', Schema::TYPE_TEXT.' NOT NULL AFTER `deliveryParam`');
	    $this->renameColumn('history', 'paymentInfo', 'paymentParam');
	    $this->alterColumn('history', 'paymentParam', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');

	    $this->renameTable('domains_shipping_payments', 'domains_delivery_payments');
	    $this->renameColumn('domains_delivery_payments', 'domainID', 'domainId');
	    $this->renameColumn('domains_delivery_payments', 'shippingID', 'deliveryType');
	    $this->addColumn('domains_delivery_payments', 'deliveryParam', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0 AFTER `deliveryType`');
	    $this->renameColumn('domains_delivery_payments', 'paymentID', 'paymentType');
	    $this->renameColumn('domains_delivery_payments', 'enable', 'enabled');
	    $this->addColumn('domains_delivery_payments', 'commissionPercent', Schema::TYPE_DOUBLE.' (12, 2) UNSIGNED NOT NULL DEFAULT 0 AFTER `paymentParam`');
		$this->addColumn('domains_delivery_payments', 'commissionStatic', Schema::TYPE_DOUBLE.' (12, 2) UNSIGNED NOT NULL DEFAULT 0 AFTER `commissionPercent`');
	    $this->dropPrimaryKey('domain', 'domains_delivery_payments');
	    $this->addPrimaryKey('domain', 'domains_delivery_payments', ['domainId', 'deliveryType', 'deliveryParam', 'paymentType', 'paymentParam']);

	    $this->batchInsert('paymentTypes', ['description', 'enabled'], [
		    ['Наложенным платежом', 1],
		    ['На банковскую карту', 1],
		    ['Наличными', 1]
	    ]);
	    $this->batchInsert('paymentParams', ['description', 'value', 'option', 'enabled'], [
		    ['Приват банк', '5168 7423 3604 9949', 'Романова Г.В.', 1],
		    ['Дельта банк', '4585 8000 0174 0453', '', 0],
		    ['Сбербанк России', '4244 9000 0528 6785', '', 0],
		    ['Золотая корона', '', '', 0]
	    ]);

	    $this->batchInsert('deliveryTypes', ['description', 'enabled'], [
		    ['Адресная доставка', 1],
		    ['На склад', 1],
		    ['Самовывоз', 1]
	    ]);
	    $this->batchInsert('deliveryParams', ['description', 'option', 'enabled'], [
		    ['Новая Почта', '', 1],
		    ['Ин-Тайм', '', 0],
		    ['Express mail', '', 0],
		    ['Склад №1', \yii\helpers\Json::encode([
			    'address'   =>  'г. Киев, ул. Электротехническая, 2',
			    'workTime'  =>  'Время работы с 9:00 до 17:00',
			    'workDays'  =>  'все дни кроме понедельника'
		    ]), 1]
		]);

	    $this->batchInsert('domains_delivery_payments', ['domainId', 'deliveryType', 'deliveryParam', 'paymentType', 'paymentParam', 'commissionPercent', 'commissionStatic', 'enabled'], [
		    [1, 1, 1, 1, 0, 2, 50, 1],
		    [1, 1, 1, 2, 1, 1, 2, 1],
		    [1, 2, 1, 1, 0, 2, 20, 1],
		    [1, 2, 1, 2, 1, 1, 2, 1],
		    [1, 3, 4, 2, 1, 1, 2, 1],
		    [1, 3, 4, 3, 0, 0, 0, 1]
	    ]);

	    $orderCount = \common\models\History::find()->count();
	    $i = 0;
	    echo "  > update history payment and delivery info for ".$orderCount." orders... \r \n";
	    foreach(\common\models\History::find()->each(100) as $order){

		    switch($order->deliveryType){
			    case '1':
				    $order->deliveryInfo = $order->deliveryAddress;
			    case '2':
					$order->deliveryParam = '1';
				    break;
			    case '3':
				    $order->deliveryParam = '3';
				    break;
			    case '5':
				    $order->orderSource = '1';
				    $order->sourceType = '1';
			    case '4':
				    $order->deliveryParam = '4';
				    break;
			    case '6':
					$order->deliveryParam = '2';
				    break;
		    }

		    switch($order->deliveryType){
			    case '2':
			    case '3':
			    case '6':
				    $order->deliveryType = '2';
				    break;
			    case '1':
				    break;
			    case '4':
			    case '5':
			    default:
				    $order->deliveryType = '3';
				    break;
		    }

		    switch($order->paymentType){
			    case '2':
				    $order->paymentParam = '1';
				    break;
			    case '3':
				    $order->paymentParam = '2';
				    break;
			    case '4':
				    $order->paymentParam = '3';
				    break;
			    case '7':
				    $order->paymentParam = '4';
				    break;
			    case '1':
			    case '5':
			    case '6':
			    default:
				    break;
		    }

		    switch($order->paymentType){
			    case '2':
			    case '3':
			    case '4':
			    case '7':
				    $order->paymentType = '2';
				    break;
			    case '1':
				    break;
			    case '5':
			    case '6':
			    default:
				    $order->paymentType = '3';
				    break;
		    }

		    $order->save(false);
		    echo "  > order ".++$i." from ".$orderCount." \r \n";
	    }
    }

	public function down()
    {
        echo "m151227_140153_oder_source_in_history cannot be reverted.\n";
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
